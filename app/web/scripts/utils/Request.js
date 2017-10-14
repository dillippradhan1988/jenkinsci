define(function (){

    function Request(){
        this.xhr;
        this.load;
        this.get;
    }

    Request.prototype.load = function(){
        if (typeof XMLHttpRequest !== 'undefined') {
            this.xhr = new XMLHttpRequest();
        } else {
            var versions = ["MSXML2.XmlHttp.5.0",
                            "MSXML2.XmlHttp.4.0",
                            "MSXML2.XmlHttp.3.0",
                            "MSXML2.XmlHttp.2.0",
                            "Microsoft.XmlHttp"]

            for (var i = 0, len = versions.length; i < len; i++) {
                try {
                    this.xhr = new ActiveXObject(versions[i]);
                    break;
                }
                catch(e){}
            }
        }
    }

    Request.prototype.get = function(url, cBf){
        this.xhr.onreadystatechange = function(e){
            if (e.currentTarget.readyState < 4) {
                return;
            }

            if (e.currentTarget.status !== 200) {
                return;
            }

            if (e.currentTarget.readyState === 4) {
                cBf(JSON.parse(e.currentTarget.responseText));
            }
        };

        this.xhr.open('GET', url, true);
        this.xhr.send();
    }

    Request.prototype.post = function(url, formData, cBf){
        this.xhr.onreadystatechange = function(e){
            if (e.currentTarget.readyState < 4) {
                return;
            }

            if (e.currentTarget.status !== 200) {
                return;
            }

            if (e.currentTarget.readyState === 4) {
                cBf(JSON.parse(e.currentTarget.responseText));
            }
        };
        this.xhr.open('POST', url, true);
        this.xhr.setRequestHeader("Content-type",
            "application/x-www-form-urlencoded");
        this.xhr.send(formData);
    }

    Request.prototype.put = function(url, formData, cBf){
        this.xhr.onreadystatechange = function(e){
            if (e.currentTarget.readyState < 4) {
                return;
            }

            if (e.currentTarget.status !== 200) {
                return;
            }

            if (e.currentTarget.readyState === 4) {
                cBf(JSON.parse(e.currentTarget.responseText));
            }
        };
        this.xhr.open('PUT', url, true);
        this.xhr.setRequestHeader("Content-type",
            "application/x-www-form-urlencoded");
        this.xhr.send(formData);
    }

    Request.prototype.delete = function(url, cBf){
        this.xhr.onreadystatechange = function(e){
            if (e.currentTarget.readyState < 4) {
                return;
            }

            if (e.currentTarget.status !== 200) {
                return;
            }

            if (e.currentTarget.readyState === 4) {
                cBf(JSON.parse(e.currentTarget.responseText));
            }
        };
        this.xhr.open('DELETE', url, true);
        this.xhr.send();
    }

    Request.prototype.postFile = function(url, formData, cBf){
        this.xhr.onreadystatechange = function(e){
            if (e.currentTarget.readyState < 4) {
                return;
            }

            if (e.currentTarget.status !== 200) {
                return;
            }

            if (e.currentTarget.readyState === 4) {
                cBf(e.currentTarget.responseText);
            }
        };

        this.xhr.addEventListener('progress', function(e) {
            var done = e.position || e.loaded, total = e.totalSize || e.total;
            //console.log('xhr progress: ' + (Math.floor(done/total*1000)/10) + '%');
        }, false);

        if ( this.xhr.upload ) {
            this.xhr.upload.onprogress = function(e) {
                var done = e.position || e.loaded, total = e.totalSize || e.total;
                //console.log('xhr.upload progress: ' + done + ' / ' + total + ' = ' + (Math.floor(done/total*1000)/10) + '%');
            };
        }

        this.xhr.open('POST', url, true);
        this.xhr.send(formData);
    }

    return Request;
});