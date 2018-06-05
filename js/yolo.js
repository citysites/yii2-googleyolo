const GoogleYolo = function (options) {
    options = options || {};

    const messageParent = function (data) {
        window.parent.postMessage(data, '*');
    };

    window.onGoogleYoloLoad = function (googleyolo) {
        openyolo.setRenderMode('navPopout');

        const hintPromise = function () {
            googleyolo.hint(options.hintConfig).then(
                function(credential) {
                    const stringCredential = JSON.stringify({type: 'credential', credential});
                    messageParent(stringCredential);
                }, function(error) {
                    const stringError = JSON.stringify({type: 'error', error});
                    messageParent(stringError);
                }
            );
        };

        if (options.allowedRetrieve) {
            googleyolo.retrieve(options.retrieveConfig).then(
                function (credential) {
                    const stringCredential = JSON.stringify({type: 'credential', credential});
                    messageParent(stringCredential);
                },
                function (error) {
                    if ('noCredentialsAvailable' === error.type) {
                        hintPromise();
                    }
                }
            );
        } else {
            hintPromise();
        }

        // GoogleYolo doesn't insert their iframe immediately. This mutation
        // observer watches the <body> to tell when they insert the <iframe> so
        // we can style as needed.
        const bodyObserver = new MutationObserver(function(mutationsList) {
            mutationsList.forEach(function(mutation) {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeName === 'IFRAME' && node.src.includes('smartlock.google.com/iframe/')) {
                        bodyObserver.disconnect();
                        node.classList.add('google-inserted-frame');
                        // We need one more observer to watch the <iframe> that Google Yolo
                        // inserted. Its style attribute will be updated with a height that
                        // needs to be passed back up to the parent iframe, to avoid any
                        // clipping. The height can change if the user is signed in with
                        // many Google accounts, and clicks the 'X more accounts' button.
                        const attributeObserver = new MutationObserver(function(iframeMutationsList) {
                            const height = iframeMutationsList[0].target.style.height;
                            messageParent({type: 'height', height});
                        });
                        attributeObserver.observe(node, { attributes: true });
                    }
                });
            });
        });
        bodyObserver.observe(window.document.body, { childList: true });
    };
};