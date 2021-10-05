"use strict";
console.log('You are running jQuery version: ' + $.fn.jquery);
$(document).ready(function(){
  $('#nav-menu li').removeClass();
  $('#nav-menu li#menu-data').addClass('open');
  $('#nav-menu li#menu-subkegiatan').addClass('active');
  $( '#pagu_subkegiatan' ).mask('000.000.000.000.000', {reverse: true});
  $( '#pagu_perubahan' ).mask('000.000.000.000.000', {reverse: true});

  $('#all-subkegiatan').DataTable();

  loadkegiatan("kegiatan",0);
  loadsubkegiatan('');

  $('#save_subkegiatan').on('click', function(){
      var kode_program = $('#kode_program').val();
      var kode_kegiatan = $('#kodkeg').val();
      var kode_subkegiatan = $('#kode_subkegiatan_1').val()+$('#kode_subkegiatan_2').val();
      var nama_subkegiatan = $('#nama_subkegiatan').val();
      var pagu_subkegiatan = $('#pagu_subkegiatan').val();
      var id_subkegiatan = $('#id_subkegiatan').val();
      var sisa_pagu_subkegiatan = $('#sisa_pagu_subkegiatan').val();
      var pagu_awal = $('#pagu_awal').val();
      var pagu_perubahan = $('#pagu_perubahan').val();
      
      if(nama_subkegiatan && pagu_subkegiatan){
        var formData = new FormData();
        formData.append('param', 'data_subkegiatan');
        formData.append('id_subkegiatan', id_subkegiatan);
        formData.append('kode_program', kode_program);
        formData.append('kode_kegiatan', kode_kegiatan);
        formData.append('kode_subkegiatan', kode_subkegiatan);
        formData.append('nama_subkegiatan', nama_subkegiatan);
        formData.append('pagu_subkegiatan', pagu_subkegiatan);
        if(id_subkegiatan){
          formData.append('sisa_pagu_subkegiatan', sisa_pagu_subkegiatan);
          formData.append('pagu_awal', pagu_awal);
          formData.append('pagu_perubahan', pagu_perubahan);
          update(formData);
        }else{
          save(formData);
        }
      }else{
        Swal.fire({
          type: 'warning',
          title: 'Harap isi Data Input !',
          showConfirmButton: true,
          // showCancelButton: true,
          confirmButtonText: `Ok`,
        })
      }
  });

$("#kode_program").chosen().change(function(){
  loadkegiatan("kegiatan",this.value);
  $('#namprog').val($('option:selected', this).attr('text'));
});

$("#nama_kegiatan").chosen().change(function(){
  $('#kodkeg').val($('option:selected', this).attr('text'));
  $('#kode_subkegiatan_1').val($('option:selected', this).attr('text')+'.');
  loadkegiatan("program",$('option:selected', this).attr('prog'));
});

$('#modal_subkegiatan').on('hidden.bs.modal', function (e) {
  $('#kode_subkegiatan_1').val('');
  $('#kode_subkegiatan_2').val('');
  $('#kode_subkegiatan_2').prop('disabled', false);
  $('#kodkeg').parent().parent().show();
  $('#nama_kegiatan_chosen').parent().parent().show();
  $('#nama_subkegiatan').val('');
  $('#id_subkegiatan').val('');
  $('#pagu_subkegiatan').val('');
  $('#sisa_pagu_subkegiatan').val('');
  $('#pagu_awal').val('');

  $('#pagu_subkegiatan').prop('disabled', false);

  $('#pagu_perubahan').parent().parent().hide();
  $('#pagu_perubahan').val('');
})


});

function loadsubkegiatan(param){

  $.ajax({
      type: 'post',
      dataType: 'json',
      url: 'loadsubkegiatan',
      data : {
              param      : param,
      },
      success: function(result){
          let data = result.data;
          var dt = $('#all-subkegiatan').DataTable({
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
                { 'mDataProp': 'kode_subkegiatan'},
                { 'mDataProp': 'nama_subkegiatan'},
                { 'mDataProp': 'pagu_subkegiatan'},
                { 'mDataProp': 'user_status'},
            ],
            order: [[0, 'ASC']],
            fixedColumns: true,
            aoColumnDefs:[
              { width: 50, targets: 0 },
              {
                  mRender: function ( data, type, row ) {

                    var el = `<button class="btn btn-xs btn-info" onclick="edit('data_subkegiatan',`+row.id+`, '`+row.kode_subkegiatan+`', '`+row.nama_subkegiatan+`', '`+row.pagu_subkegiatan+`', '`+row.sisa_pagu_subkegiatan+`', '`+row.pagu_perubahan+`')">
                    <i class="ace-icon fa fa-edit bigger-120"></i>
                  </button>
                              <button class="btn btn-xs btn-danger" onclick="action('data_subkegiatan', `+row.id+`)">
          																<i class="ace-icon fa fa-trash-o bigger-120"></i>
          															</button>`;

                      return el;
                  },
                  aTargets: [6]
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

function loadkegiatan(param, code){
    var formData = new FormData();
    formData.append('code', code);

  $.ajax({
      type: 'post',
      processData: false,
      contentType: false,
      url: 'load'+param,
      data : formData,
      success: function(result){

        let data = result.data;
        let el1   = '<option value=""></option>';
        let el2   = '<option value=""></option>';
        if(typeof data == 'object'){
          for (var i = 0; i < data.length; i++) {
            el1 += '<option value="'+data[i].kode_program+'" text="'+data[i].nama_program+'" selected>'+data[i].kode_program+'</option>';
            el2 += '<option value="'+data[i].kode_kegiatan+'" text="'+data[i].kode_kegiatan+'" prog="'+data[i].kode_program+'">'+data[i].nama_kegiatan+'</option>';
          }
        }

        if(param == 'program'){
          $('#kode_program').html(el1);
          $('#kode_program').trigger("chosen:updated");
        }else if(param == 'kegiatan'){
          $('#nama_kegiatan').html(el2);
          $('#nama_kegiatan').trigger("chosen:updated");
        }
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
          title: 'Berhasil Tambah Sub Kegiatan !',
          showConfirmButton: true,
          // showCancelButton: true,
          confirmButtonText: `Ok`,
        }).then((result) => {
          $(document).ready(function(){
            // loadsubkegiatan('');
            //   $('#kode_program').val(0).trigger("chosen:updated");
            //   $('#kode_kegiatan').val(0).trigger("chosen:updated");
            //   $('#kode_subkegiatan').val('');
            //   $('#nama_subkegiatan').val('');
            location.reload()

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
        url: 'updatesubKegiatan',
        data : formData,
        success: function(result){
          Swal.fire({
            type: 'success',
            title: 'Berhasil Update Kegiatan !',
            showConfirmButton: true,
            // showCancelButton: true,
            confirmButtonText: `Ok`,
          }).then((result) => {
            $(document).ready(function(){
              location.reload()
  
            });
          })
        }
      });
    };

  function action(table, id){
          bootbox.confirm({
            message: "Anda Yakin <b>Hapus</b> Sub Kegiatan ini?",
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
                      loadsubkegiatan('');
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

    function edit(table,id, code, name, pagu, sisa, pagu_perubahan){
      $('#modal_subkegiatan').modal('show');
      let mycode = code.split(".");
      
      $('#kode_subkegiatan_1').val(code.slice(0, -mycode[mycode.length - 1].length));
      $('#kode_subkegiatan_2').val(mycode[mycode.length - 1]);
      $('#kode_subkegiatan_2').prop('disabled', true);
      $('#kodkeg').parent().parent().hide();
      $('#nama_kegiatan_chosen').parent().parent().hide();
      $('#nama_subkegiatan').val(name);
      $('#id_subkegiatan').val(id);
      $('#pagu_subkegiatan').val(pagu);
      $('#sisa_pagu_subkegiatan').val(sisa);
      $('#pagu_awal').val(pagu);
      $('#pagu_subkegiatan').prop('disabled', true);
      
      $('#pagu_perubahan').val(pagu_perubahan == 'null' ? '': pagu_perubahan);
      $('#pagu_perubahan').parent().parent().show();
    
    }
