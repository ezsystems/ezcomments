var eZComments = function() {
    var ret = {};
    
    // Private
    
    var yCallback = function(Y, result) {
        
        //Shared variables
        
        var sessionID = Y.Cookie.get(ret.cfg.sessionprefix);
        var encodedUserData = Y.Cookie.get('eZCommentsUserData');
        
        var userDataCallback = function(id, o) {
            if (o.responseJSON !== undefined) {
                var response = o.responseJSON;
                var userDataObject = response.content;
                if(userDataObject!=''){
                    updateInputFields(userDataObject);
                }
            }
        }

       
        
        var updateInputFields = function(userDataObject) {
            if (Y.Object.hasKey(userDataObject, sessionID)) {
                var userData = Y.Object.getValue(userDataObject, sessionID);
                var fieldName = Y.get(ret.cfg.fields.name);
                if (fieldName!=null){
                    fieldName.set('value', userData.name);
                }
                var fieldEmail = Y.get(ret.cfg.fields.email);
                if (fieldEmail!=null){
                    fieldEmail.set('value', userData.email);
                }
            } else {
                return false;
            }
            
            return true;
        }

        var fetchUserData = false;

        if (encodedUserData) {
            var decodedUserData = Y.Base64.decode(encodedUserData);
            var userDataObject = Y.JSON.parse(decodedUserData);
            
            if (updateInputFields(userDataObject) == false) {
                fetchUserData = true;
            }
        } else {
            fetchUserData = true;
        }
        
        if ( fetchUserData ) {
            Y.io.ez('ezcom::userdata', { on: { success: userDataCallback } });
        }

    }

    // Public
    
    ret.cfg = {};
    
    ret.init = function() {
        YUI3_config.modules = {
                'gallery-base64': {
                    fullpath: 'http://yui.yahooapis.com/gallery-2009.12.08-22/build/gallery-base64/gallery-base64-min.js'
                }
        }
        var ins = YUI(YUI3_config).use('node','event','cookie','json-parse','io-form','io-ez','gallery-base64',yCallback);
    }
    
    return ret;
}();