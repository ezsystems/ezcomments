var eZComments = function() {
    var ret = {};
    
    // Private
    
    var yCallback = function(Y, result) {
        Y.one(ret.cfg.postbutton).on('click', function(e) {
            e.preventDefault();
            console.log(Y.one(ret.cfg.postform));
            Y.io.ez('ezcom::postcomment', { method: 'POST', 
                                            on: { success: successCallback },
                                            form: { id: Y.one(ret.cfg.postform), 
                                                    useDisabled: true,
                                                    upload: false } });
        });
    }
    
    var successCallback = function(o) {
        if (o.responseJSON !== undefined) {
            var response = o.responseJSON;
        }
    }    
    // Public
    
    ret.cfg = {};
    
    ret.init = function() {
        var ins = YUI(YUI3_config).use('node','event','io-form','io-ez', yCallback);
    }
    
    return ret;
}();