"use strict";
console.log('You are running jQuery version: ' + $.fn.jquery);
$(document).ready(function(){
  $('#nav-menu li').removeClass();
  $('#nav-menu li#menu-data').addClass('open');
  $('#nav-menu li#menu-program').addClass('active');

  $('#all-program').DataTable();

  loadprogram('');

  $('#save_program').on('click', function(){
      var kode_program = $('#kode_program').val();
      var nama_program = $('#nama_program').val();
      var id_program = $('#id_program').val()

      var formData = new FormData();
      formData.append('param', 'data_program');
      formData.append('id_program', id_program);
      formData.append('kode_program', kode_program);
      formData.append('nama_program', nama_program);
      if(id_program){
        update(formData);
      }else{
        save(formData);
      }
  });

  $('#modal_program').on('hidden.bs.modal', function (e) {
    $('#kode_program').val('');
    $('#kode_program').prop('disabled', false);
    $('#nama_program').val('');
    $('#id_program').val('')
  })

});

function loadprogram(param){

  $.ajax({
      type: 'post',
      dataType: 'json',
      url: 'loadprogram',
      data : {
              param      : param,
      },
      success: function(result){
          let data = result.data;
          var dt = $('#all-program').DataTable({
            destroy: true,
            paging: true,
            lengthChange: false,
            searching: true,
            ordering: true,
            info: true,
            autoWidth: false,
            responsive: false,
            pageLength: 10,
            aaData: result.data,
            aoColumns: [
                { 'mDataProp': 'id', 'width':'10%'},
                { 'mDataProp': 'kode_program'},
                { 'mDataProp': 'nama_program'},
                { 'mDataProp': 'user_status'},
            ],
            order: [[0, 'ASC']],
            fixedColumns: true,
            aoColumnDefs:[
              { width: 50, targets: 0 },
              {
                  mRender: function ( data, type, row ) {

                    var el = `<button class="btn btn-xs btn-info" onclick="edit('data_program',`+row.id+`, '`+row.kode_program+`', '`+row.nama_program+`')">
                                <i class="ace-icon fa fa-edit bigger-120"></i>
                              </button>
                              <button class="btn btn-xs btn-danger" onclick="action('data_program',`+row.id+`)">
          																<i class="ace-icon fa fa-trash-o bigger-120"></i>
          															</button>`;

                      return el;
                  },
                  aTargets: [3]
              },
            ],
            fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull){
                var index = iDisplayIndexFull + 1;
                $('td:eq(0)', nRow).html('#'+index);
                return  index;
            },
            fnInitComplete: function () {

                var that = this;
                var td ;
                var tr ;
                this.$('td').click( function () {
                    td = this;
                });
                this.$('tr').click( function () {
                    tr = this;
                });
            }
        });

        let first_row = dt.row(':first').data();
        $('#satuan_code').val(parseInt(first_row.id) + 1 + '0');

        }
      })
    }

function save(formData){

  $.ajax({
      type: 'post',
      processData: false,
      contentType: false,
      url: 'addProgram',
      data : formData,
      success: function(result){
        Swal.fire({
          type: 'success',
          title: 'Berhasil Tambah Program !',
          showConfirmButton: true,
          // showCancelButton: true,
          confirmButtonText: `Ok`,
        }).then((result) => {
          $(document).ready(function(){
              loadprogram('');
              $('#kode_program').val('');
              $('#nama_program').val('');

          });
        })
      }
    });
  };

  function update(formData){

    $.ajax({
        type: 'post',
        processData: false,
        contentType: false,
        url: 'updateProgram',
        data : formData,
        success: function(result){
          Swal.fire({
            type: 'success',
            title: 'Berhasil Update Program !',
            showConfirmButton: true,
            // showCancelButton: true,
            confirmButtonText: `Ok`,
          }).then((result) => {
            $(document).ready(function(){
                loadprogram('');
                $('#kode_program').val('');
                $('#nama_program').val('');
  
            });
          })
        }
      });
    };

  function action(table, id){
    bootbox.confirm({
      message: "Anda Yakin <b>Hapus</b> Program ini?",
      buttons: {
      confirm: {
          label: '<i class="fa fa-check"></i> Ya',
          className: 'btn-success btn-xs',
      },
      cancel: {
          label: '<i class="fa fa-times"></i> Tidak',
          className: 'btn-danger btn-xs',
      }
    },
    callback : function(result) {
    if(result) {
      var formData = new FormData();
      formData.append('table', table);
      formData.append('id', id);
        $.ajax({
          type: 'post',
          processData: false,
          contentType: false,
          url: 'deleteData',
          data : formData,
          success: function(result){
            Swal.fire({
              type: 'warning',
              title: 'Berhasil Hapus Program !',
              showConfirmButton: true,
              // showCancelButton: true,
              confirmButtonText: `Ok`,
            }).then((result) => {
              $(document).ready(function(){
                loadprogram('');
                  $('#kode_program').val(0).trigger("chosen:updated");
                  $('#kode_kegiatan').val(0).trigger("chosen:updated");
                  $('#kode_subkegiatan').val('');
                  $('#nama_subkegiatan').val('');
    
              });
            })
          }
        });
      }
    }
});

};

function edit(table,id, code, name){
  $('#modal_program').modal('show');
  $('#kode_program').val(code);
  $('#kode_program').prop('disabled', true);
  $('#nama_program').val(name);
  $('#id_program').val(id);
  

}


