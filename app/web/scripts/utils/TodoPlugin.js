define(function (){

    function TodoPlugin(){

    }

    TodoPlugin.prototype.getProducts = function(url, reqObj, cBf){
        reqObj.get(url, function(res){
            if(typeof cBf == "function"){
                cBf(res);
            }
        });
    }

    TodoPlugin.prototype.createProduct = function(url, formData, reqObj, cBf){
        var formData = "productName="+encodeURIComponent(formData.productName);
        reqObj.post(url, formData, function(res){
            if(typeof cBf == "function"){
                cBf(res);
            }
        });
    }

    TodoPlugin.prototype.updateProduct = function(url, formData, reqObj, cBf){
        var formData = "productName="+encodeURIComponent(formData.productName);
        reqObj.put(url, formData, function(res){
            if(typeof cBf == "function"){
                cBf(res);
            }
        });
    }

    TodoPlugin.prototype.deleteProduct = function(url, reqObj, cBf){
        reqObj.delete(url, function(res){
            if(typeof cBf == "function"){
                cBf(res);
            }
        });
    }

    TodoPlugin.prototype.uploadFile = function(url, formData, reqObj, cBf){
        var fd = new FormData();
        var files = new Array();
        for(var i in formData){
            fd.append("files"+ i, formData[i]);
        }
        fd.append("art-file-name", document.getElementById('art-file-name').value);
        reqObj.postFile(url, fd, function(res){
            if(typeof cBf == "function"){
                cBf(res);
            }
        });
    }

    return TodoPlugin;
});