<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bilfen Case</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-12"><br><br><br></div>
            <div class="col-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Tc No</th>
                            <th scope="col">İsim</th>
                            <th scope="col">Soyisim</th>
                            <th scope="col">Okul</th>
                            <th scope="col">Okul No</th>
                            <th scope="col">İşlem</th>
                        </tr>
                    </thead>
                    <tbody id="listArea">

                    </tbody>
                </table>
            </div>
            <div class="col-12">
                <nav aria-label="Page navigation example">
                    <ul class="pagination" id="pageNumberArea">
                        
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" id="editModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Öğrenci Düzenle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <label for="editModal_name" class="form-label">İsim</label>
                            <input type="text" class="form-control" id="editModal_name">
                        </div>
                        <div class="col-12">
                            <label for="editModal_surname" class="form-label">Soyisim</label>
                            <input type="text" class="form-control" id="editModal_surname">
                        </div>
                        <div class="col-12">
                            <label for="editModal_schoolno" class="form-label">Okul No</label>
                            <input type="text" class="form-control" id="editModal_schoolno">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="saveEdit()">Kaydet</button>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

    let allData=null,
        selectedId=null,
        selectedPageNumber=1,
        totalPageNumber=0;

    $(document).ready(function() {
        getList();
    });

    function saveEdit(){
        if(selectedId!=null){
            $.ajax({
                url      :'api/v1/students/'+selectedId+'?_method=PUT',
                type     :'POST',
                data     :{
                    name:$('#editModal_name').val(),
                    surname:$('#editModal_surname').val(),
                    school_no:$('#editModal_schoolno').val(),
                },
                dataType :'json',
                cache    :false,
                sync     :false,
                async    :false,
                global   :false,
                headers: {
                    "Authorization": "Bearer 1|4PqK7cvRkHcLkqE7cwXtWgMlWflONWSm71cJ957L"
                },
                success:function(data){
                    console.log(data);
                    if(data.status){
                        $.each(allData,function(k,v){
                            if(v.id==selectedId){
                                allData[k].name=$('#editModal_name').val();
                                allData[k].surname=$('#editModal_surname').val();
                                allData[k].school_no=$('#editModal_schoolno').val();
                                return false;
                            }
                        });
                        listData(allData);
                        $('#editModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Başarılı',
                            html:'Güncelleme İşlemi Başarılı',
                            confirmButtonText:'Tamam'
                        });
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Hata',
                            html: data.message,
                            confirmButtonText:'Tamam'
                        });
                    }
                },
                error:function(data){
                    console.log(data);
                    Swal.fire({
                        icon: 'error',
                        title: 'Hata',
                        html: 'Sistem Hatası',
                        confirmButtonText:'Tamam'
                    });
                }
            });
        }
    }

    function editStudent(id){
        $('#editModal_name').val('');
        $('#editModal_surname').val('');
        $('#editModal_schoolno').val('');
        $.each(allData,function(k,v){
            if(v.id==id){
                selectedId=id;
                $('#editModal_name').val(v.name);
                $('#editModal_surname').val(v.surname);
                $('#editModal_schoolno').val(v.school_no);
                $('#editModal').modal('show');
                return false;
            }
        });
    }

    function removeStudent(id){
        Swal.fire({
            icon: 'warning',
            title: 'Uyarı',
            html: 'Silmek istediğinize emin misiniz ?',
            confirmButtonText:'Sil',
            showCancelButton: true,
            cancelButtonText: 'Vazgeç'
        }).then((result)=>{
            if(result.value){
                $.ajax({
                    url      :'api/v1/students/'+id,
                    type     :'DELETE',
                    data     :{},
                    dataType :'json',
                    cache    :false,
                    sync     :false,
                    async    :false,
                    global   :false,
                    headers: {
                        "Authorization": "Bearer 1|4PqK7cvRkHcLkqE7cwXtWgMlWflONWSm71cJ957L"
                    },
                    success:function(data){
                        console.log(data);
                        if(data.status){
                            $.each(allData,function(k,v){
                                if(v.id==id){
                                    allData.splice(k,1);
                                    return false;
                                }
                            });
                            listData(allData);
                            Swal.fire({
                                icon: 'success',
                                title: 'Başarılı',
                                html:'Silme İşlemi Başarılı',
                                confirmButtonText:'Tamam'
                            });
                        }else{
                            Swal.fire({
                                icon: 'error',
                                title: 'Hata',
                                html: data.message,
                                confirmButtonText:'Tamam'
                            });
                        }
                    },
                    error:function(data){
                        console.log(data);
                        Swal.fire({
                            icon: 'error',
                            title: 'Hata',
                            html: 'Sistem Hatası',
                            confirmButtonText:'Tamam'
                        });
                    }
                });
            }
        });   
    }

    function getList(){
        $.ajax({
            url      :'api/v1/students?dataLimit=5&pageNumber='+selectedPageNumber,
            type     :'GET',
            data     :{},
            dataType :'json',
            cache    :false,
            sync	 :false,
            async    :false,
            global   :false,
            headers: {
                "Authorization": "Bearer 1|4PqK7cvRkHcLkqE7cwXtWgMlWflONWSm71cJ957L"
            },
            success:function(data){
                console.log(data);
                if(data.status){
                    allData=data.data;
                    totalPageNumber=parseInt(data.totalPage[0].totalPageNumber);
                    listData(allData);
                    listPageNumbers();
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Hata',
                        html: data.message,
                        confirmButtonText:'Tamam'
                    });
                }
            },
            error:function(data){
                console.log(data);
                Swal.fire({
                    icon: 'error',
                    title: 'Hata',
                    html: 'Sistem Hatası',
                    confirmButtonText:'Tamam'
                });
            }
        });
    }

    function listData(data){
        let html='';
        $.each(data,function(k,v){
            html+='<tr>';
                html+='<td>'+v.id+'</td>';
                html+='<td>'+v.tcno+'</td>';
                html+='<td>'+v.name+'</td>';
                html+='<td>'+v.surname+'</td>';
                html+='<td>'+v.school_id+'</td>';
                html+='<td>'+v.school_no+'</td>';
                html+='<td><button class="btn btn-sm btn-primary me-1" onclick="editStudent('+v.id+')">Düzenle</button><button class="btn btn-sm btn-danger" onclick="removeStudent('+v.id+')">Sil</button></td>';
            html+='</tr>';
        });
        $('#listArea').html(html);
    }

    function backPage(){
        if(selectedPageNumber>1){
            selectedPageNumber--;
        }
        getList();
    }

    function nextPage(){
        if(selectedPageNumber<totalPageNumber){
            selectedPageNumber++;
        }
        getList();
    }

    function selectPage(pageNumber){
        selectedPageNumber=pageNumber;
        getList();
    }

    function listPageNumbers(){
        let html='';
        html+='<li class="page-item"><a class="page-link" href="javascript:backPage();">Geri</a></li>';
        for(var i = 1; i < (totalPageNumber+1); i++){
            html+='<li class="page-item '+(selectedPageNumber==i?'active':'')+'"><a class="page-link" href="javascript:selectPage('+i+');">'+i+'</a></li>';   
        }
        html+='<li class="page-item"><a class="page-link" href="javascript:nextPage();">İleri</a></li>';
        $('#pageNumberArea').html(html);
    }

</script>