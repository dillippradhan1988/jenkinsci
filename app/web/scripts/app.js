define(['utils/TodoPlugin', 'utils/Request'], function(TodoPlugin, Request){

    var requestObj = new Request();
    var todoPluginObj = new TodoPlugin();

    function App(requestObj, todoPluginObj, baseUrl){
        this.requestObj = requestObj;
        this.todoPluginObj = todoPluginObj;
        this.baseUrl = baseUrl;
    }

    App.prototype = {
        initReq : function(){
            this.requestObj.load();
        },

        loadData : function(){
            this.todoPluginObj.getProducts(
                this.baseUrl+'/index.php/api/v1/products',
                this.requestObj,
                function(res){
                    var tblObj = '<table id="listTbl" class="table ';
                        tblObj +=   'table-bordered table-striped ';
                        tblObj +=   ' product-list-dv_tbl">';
                        tblObj +=   '<tbody>';


                    if(res && res.length){
                        tblObj +=   '<tr>';
                        tblObj +=       '<th>No.</th>';
                        tblObj +=       '<th>Product Name</th>';
                        tblObj +=       '<th>Update</th>';
                        tblObj +=       '<th>Delete</th>';
                        tblObj +=   '</tr>';

                        for(var i in res){
                            if(res[i]['productName']){
                                tblObj +=   '<tr>';
                                tblObj +=    '<th>'+(parseInt(i, 10)+1)+'</th>';
                                tblObj +=    '<th>'+res[i]['productName']+'</th>';
                                tblObj +=       '<th>';
                                tblObj +=           '<button data-id="';
                                tblObj +=           res[i]['id']+'"';
                                tblObj +=           ' class="btn btn-warning ';
                                tblObj +='product-list-dv_tbl_edit_btn"> Update';
                                tblObj +=           '</button>';
                                tblObj +=       '</th>';
                                tblObj +=       '<th>';
                                tblObj +=           '<button data-id="'+res[i]['id']+'"';
                                tblObj +=           ' class="btn btn-danger ';
                                tblObj += 'product-list-dv_tbl_del_btn"> Delete';
                                tblObj +=           '</button>';
                                tblObj +=       '</th>';
                                tblObj +=   '</tr>';
                            }
                        }
                    }else{
                        tblObj +=   '<tr>';
                        tblObj +=       '<td colspan="4" align="center">';
                        tblObj +=           'No Record Found.';
                        tblObj +=       '</td>';
                        tblObj +=   '</tr>';
                    }
                    tblObj +=   '</tbody>';
                    tblObj += '</table>';
                    document.getElementById("product-list-dv").innerHTML
                    = tblObj;

                    var editBtnObj = document.getElementsByClassName(
                                        "product-list-dv_tbl_edit_btn");
                    for (var i = 0; i < editBtnObj.length; i++) {
                        editBtnObj[i].addEventListener('click', editData, false);
                    }

                    var deleteBtnObj = document.getElementsByClassName(
                                        "product-list-dv_tbl_del_btn");
                    for (var i = 0; i < deleteBtnObj.length; i++) {
                        deleteBtnObj[i].addEventListener('click', deleteData,
                                         false);
                    }
            });
        }
    };

    var appObj = new App(requestObj, todoPluginObj, baseUrl);
    appObj.initReq();

    //appObj.loadData();

    if(document.getElementById('save-btn')){
        document.getElementById("save-btn").onclick = function(){
            var id = document.getElementById('product-id').value;
            var productName = document.getElementById('product-name').value;
            if(productName == ""){
                alert("Please enter Product Name");
                return false;
            }

            var formData = {
                            productName : productName
                        };
            if(id){
                todoPluginObj.updateProduct(
                    baseUrl+'/index.php/api/v1/products/'+id,
                    formData,
                    requestObj,
                    function(res){
                        document.getElementById("save-btn").innerHTML = 'Save';
                        document.getElementById('product-id').value = '';
                        document.getElementById('product-name').value = '';
                        appObj.loadData();
                });
            }else{
              todoPluginObj.createProduct(
                    baseUrl+'/index.php/api/v1/products',
                    formData,
                    requestObj,
                    function(res){
                        document.getElementById('product-id').value = '';
                        document.getElementById('product-name').value = '';
                        appObj.loadData();
                });
            }
        }
    }


    var editData = function() {
        var id = this.getAttribute("data-id");
        todoPluginObj.getProducts(
            baseUrl+'/index.php/api/v1/products/'+id,
            requestObj,
            function(res){
                if(res){
                    document.getElementById("save-btn").innerHTML = 'Update';
                    document.getElementById("product-id").value = res.id;
                    document.getElementById("product-name").value
                    = res.productName;
                }
        });
    };

    var deleteData = function() {
        var id = this.getAttribute("data-id");
        todoPluginObj.deleteProduct(
            baseUrl+'/index.php/api/v1/products/'+id,
            requestObj,
            function(res){
                appObj.loadData();
        });

    };

    if(document.getElementById('upload-file')){
        document.getElementById('upload-file').addEventListener(
            'change', function(e) {
            var uploadFileName =
            document.getElementById('art-file-name').value;
            var uploadFile = this.files;

            if (uploadFileName == '') {
                document.getElementById('upload-file').value = '';
                alert('Please enter file name.');
                return false;
            }
            // if (uploadFile.type !== 'application/zip') {
            //     alert('Invalid FIle.');
            //     return false;
            // } else if (uploadFile.size > 2097152){
            //     alert('File size should not be more than 2MB.');
            //     return false;
            // } else {
                todoPluginObj.uploadFile(
                    baseUrl+'/index.php/uploads',
                    uploadFile,
                    requestObj,
                    function(res){
                        document.getElementById('product-list-main-div').
                        style.display = "block";
                        document.getElementById('product-list-dv').innerHTML = res;
                        document.getElementById('upload-file').value = '';

                        window.location.href = baseUrl+'/index.php/download/' + res;                });
            // }
        }, false);
    }

});