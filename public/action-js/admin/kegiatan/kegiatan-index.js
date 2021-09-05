"use strict";
console.log('You are running jQuery version: ' + $.fn.jquery);
$(document).ready(function(){
  $('#nav-menu li').removeClass();
  $('#nav-menu li#menu-data').addClass('open');
  $('#nav-menu li#menu-kegiatan').addClass('active');

  $('#all-kegiatan').DataTable();

  loadprogram('');
  loadkegiatan('');

  $('#save_kegiatan').on('click', function(){
      var kode_program = $('#kodprog').val();
      var kode_kegiatan = $('#kode_kegiatan_1').val()+$('#kode_kegiatan_2').val();
      var nama_kegiatan = $('#nama_kegiatan').val();

      var formData = new FormData();
      formData.append('param', 'data_kegiatan');
      formData.append('kode_program', kode_program);
      formData.append('kode_kegiatan', kode_kegiatan);
      formData.append('nama_kegiatan', nama_kegiatan);
      save(formData);
  });

  $('#nama_program').on('change', function(){
    
    let toy = $('option:selected', this).attr('text');
    
    $('#kodprog').val(toy);
    $('#kode_kegiatan_1').val(toy+'.');

  })

});

function loadkegiatan(param){

  $.ajax({
      type: 'post',
      dataType: 'json',
      url: 'loadkegiatan',
      data : {
              param      : param,
      },
      success: function(result){
          let data = result.data;
          var dt = $('#all-kegiatan').DataTable({
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
                { 'mDataProp': 'kode_kegiatan'},
                { 'mDataProp': 'nama_kegiatan'},
                { 'mDataProp': 'user_status'},
            ],
            order: [[0, 'ASC']],
            fixedColumns: true,
            aoColumnDefs:[
              { width: 50, targets: 0 },
              {
                  mRender: function ( data, type, row ) {

                    var el = `
                              <button class="btn btn-xs btn-danger" onclick="action('data_kegiatan',`+row.id+`)">
          																<i class="ace-icon fa fa-trash-o bigger-120"></i>
          															</button>`;

                      return el;
                  },
                  aTargets: [4]
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
          let el   = '<option value="">  </option>';
          for (var i = 0; i < data.length; i++) {
            el += '<option value="'+data[i].kode_program+'" text="'+data[i].kode_program+'">'+data[i].nama_program+'</option>';
          }
          $('#nama_program').append(el);
          $('#nama_program').trigger("chosen:updated");

        }
      })
    }

function save(formData){

  $.ajax({
      type: 'post',
      processData: false,
      contentType: false,
      url: 'addKegiatan',
      data : formData,
      success: function(result){
        Swal.fire({
          type: 'success',
          title: 'Berhasil Tambah Kegiatan !',
          showConfirmButton: true,
          // showCancelButton: true,
          confirmButtonText: `Ok`,
        }).then((result) => {
          $(document).ready(function(){
              loadkegiatan('');
              $('#kode_program').val(0).trigger("chosen:updated");
              $('#kode_kegiatan').val('');
              $('#nama_kegiatan').val('');

          });
        })
      }
    });
  };

  function action(table, id){
    bootbox.confirm({
      message: "Anda Yakin <b>Hapus</b> Kegiatan ini?",
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
              title: 'Berhasil Hapus Sub Kegiatan !',
              showConfirmButton: true,
              // showCancelButton: true,
              confirmButtonText: `Ok`,
            }).then((result) => {
              $(document).ready(function(){
                loadkegiatan('');
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