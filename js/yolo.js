const GoogleYolo = function (options) {
    options = options || {};

    const messageParrent = function (data) {
        window.parent.postMessage(data, '*');
    };

    window.onGoogleYoloLoad = function (googleyolo) {
        openyolo.setRenderMode('navPopout');

        const hintPromise = function () {
            googleyolo.hint(options.hintConfig).then(
                function(credential) {
                    const stringCredential = JSON.stringify({type: 'credential', credential});
                    messageParrent(stringCredential);
                }, function(error) {
                    const stringError = JSON.stringify({type: 'error', error});
                    messageParrent(stringError);
                }
            );
        };

        if (options.allowedRetrieve) {
            googleyolo.retrieve(options.retrieveConfig).then(
                function (credential) {
                    const stringCredential = JSON.stringify({type: 'credential', credential});
                    messageParrent(stringCredential);
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
    };
};