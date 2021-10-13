"use strict";
console.log('You are running jQuery version: ' + $.fn.jquery);
$(document).ready(function(){
  $('#nav-menu li').removeClass();
  $('#nav-menu li#menu-data').addClass('open');
  $('#nav-menu li#menu-paket').addClass('active');
  $( '#pagu_paket' ).mask('000.000.000.000.000', {reverse: true});
  $( '#pagu_perubahan' ).mask('000.000.000.000.000', {reverse: true});
  $('#pagu_perubahan').parent().parent().hide();

  $('#all-paket').DataTable();

  loadkegiatan("subkegiatan",0);
  loadpaket('');

  $('#save_paket').on('click', function(){
      var kode_program = $('#kode_program').val();
      var kode_kegiatan = $('#kode_kegiatan').val();
      var kode_subkegiatan = $('#nama_subkegiatan').val();
      var nama_paket = $('#nama_paket').val();
      var kode_paket = $('#kodpak_1').val()+$('#kodpak_2').val();
      var pagu_paket = $('#pagu_paket').val();
      var sisa_pagu = $('#pagu_subkegiatan').val().replaceAll('.', '') - $('#pagu_paket').val().replaceAll('.', '');
      var id_paket = $('#id_paket').val();

      if(kode_program && kode_kegiatan && kode_subkegiatan && nama_paket && kode_paket && pagu_paket){
        var formData = new FormData();
        formData.append('param', 'data_paket');
        formData.append('kode_program', kode_program);
        formData.append('id_paket', id_paket);
        formData.append('kode_kegiatan', kode_kegiatan);
        formData.append('kode_subkegiatan', kode_subkegiatan);
        formData.append('nama_paket', nama_paket);
        formData.append('kode_paket', kode_paket);
        formData.append('pagu_paket', pagu_paket);
        formData.append('sisa_pagu', sisa_pagu);

        if(id_paket){
          var pagu_perubahan = $('#pagu_perubahan').val();
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

$("#kode_kegiatan").chosen().change(function(){
  loadkegiatan("subkegiatan",this.value);
  $('#namkeg').val($('option:selected', this).attr('text'));
});

$("#nama_subkegiatan").chosen().change(function(){
  $('#kodsub').val($('option:selected', this).attr('text'));
  $('#kodpak_1').val($('option:selected', this).attr('text') +'.');
  $('#pagu_subkegiatan').val($('option:selected', this).attr('pagu_sub'));
  loadkegiatan("kegiatan",'',$('option:selected', this).attr('keg'));
  loadkegiatan("program",$('option:selected', this).attr('prog'));
  loadkegiatan("paket",this.value);
});

  $('#pagu_paket').on('keyup', function(){
    let pagusub = $('#pagu_subkegiatan').val().replaceAll('.', '');
    let pagupak = this.value.replaceAll('.', '');
    if(parseInt(pagupak) > parseInt(pagusub)){
      $('#save_paket').prop('disabled', true);
      $('#pagu_paket').parent().parent().addClass('has-error');
      $('#pagu_habis').show();
    }else{
      $('#save_paket').prop('disabled', false);
      $('#pagu_paket').parent().parent().removeClass('has-error');
      $('#pagu_habis').hide();
    }
  })

  $('#modal_paket').on('hidden.bs.modal', function (e) {
    $('#nama_subkegiatan').val('');
    $('#nama_subkegiatan').chosen().change();
    $(".chosen-select").prop('disabled', false);
    $('#nama_subkegiatan').trigger("chosen:updated");
    $('#kodpak_1').val('');
    $('#kodpak_2').val('');
    $('#kodpak_2').prop('disabled', true);

    $('#nama_paket').val('');
    $('#id_paket').val('');
    $('#pagu_paket').val('');
  })

  $('#modal_paket').on('hidden.bs.modal', function (e) {
    $('#pagu_paket').prop('disabled', false);
    $('#pagu_perubahan').parent().parent().hide();
    $('#pagu_perubahan').val('');
  })


});

function loadpaket(param){

  $.ajax({
      type: 'post',
      dataType: 'json',
      url: 'loadpaket',
      data : {
              param      : param,
      },
      success: function(result){
          let data = result.data;
          let code = result.code;
          if(code == '1'){
            var dt = $('#all-paket').DataTable({
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
                  { 'mDataProp': 'id', 'width':'10px'},
                  { 'mDataProp': 'kode_program', 'width':'10px'},
                  { 'mDataProp': 'kode_kegiatan', 'width':'10px'},
                  { 'mDataProp': 'kode_subkegiatan', 'width':'10px'},
                  { 'mDataProp': 'nama_paket'},
                  { 'mDataProp': 'kode_paket', 'width':'10px' },
                  { 'mDataProp': 'pagu_paket', 'className': "text-right"},
                  { 'mDataProp': 'user_status', 'width':'10px'},
              ],
              order: [[0, 'ASC']],
              fixedColumns: true,
              aoColumnDefs:[
                { width: 50, targets: 0 },
                // {
                //   mRender: function ( data, type, row ) {

                //     var el = `<a href="rencana?param=insert&ids=`+row.id+`">`+data+`</a>`;

                //       return el;
                //   },
                //   aTargets: [4]
                // },
                {
                    mRender: function ( data, type, row ) {

                    //   var el = `<button class="btn btn-xs btn-info" onclick="edit('data_paket',`+row.id+`, '`+row.kode_paket+`', '`+row.nama_paket+`', '`+row.pagu_paket+`', '`+row.kode_subkegiatan+`', '`+row.pagu_perubahan+`')">
                    //   <i class="ace-icon fa fa-edit bigger-120"></i>
                    // </button>`
                    //       el += `<button class="btn btn-xs btn-danger" onclick="action('data_paket',`+row.id+`)">
                    //                         <i class="ace-icon fa fa-trash-o bigger-120"></i>
                    //                       </button>`;
                    //     if(row.target == 0){
                    //       el += `<a class="btn btn-xs btn-success" href="rencana?param=insert&ids=`+row.id+`">
                    //                 <i class="ace-icon fa fa-check-square-o bigger-120"></i>
                    //               </a>`
                    //     }else{
                    //       el += `<a class="btn btn-xs btn-success" href="rencana?param=view&ids=`+row.idtarget+`">
                    //                 <i class="ace-icon fa fa-check-square-o bigger-120"></i>
                    //               </a>`
                    //     }

                        var el = `<div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-primary btn-block btn-xs dropdown-toggle" aria-expanded="false">
                              Action
                              <i class="ace-icon fa fa-angle-down icon-on-right"></i>
                            </button>

                            <ul class="dropdown-menu dropdown-menu-right">
                              <li>
                                <a onclick="edit('data_paket',`+row.id+`, '`+row.kode_paket+`', '`+row.nama_paket+`', '`+row.pagu_paket+`', '`+row.kode_subkegiatan+`', '`+row.pagu_perubahan+`')">Edit Paket</a>
                              </li>

                              <li>
                                <a onclick="action('data_paket',`+row.id+`)">Hapus Paket</a>
                              </li>

                              <li class="divider"></li>
                              `;

                              if(row.target == 1){
                                el += `<li>
                                    <a href="rencana?param=edit&ids=`+row.idtarget+`">Edit Rencana</a>
                                  </li>
                                  <li>
                                    <a href="rencana?param=view&ids=`+row.idtarget+`">Lihat Rencana</a>
                                  </li>`;

                              }else{
                                el += `<li>
                                    <a href="rencana?param=insert&ids=`+row.id+`">Tambah Rencana</a>
                                  </li>`;
                              }

                          
                        el += `</ul>
											</div>`

                        return el;
                    },
                    aTargets: [7]
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
          }else{
            var table = $('#all-paket').DataTable();
                table.clear().draw();
          }

        }
      })
    }

function loadkegiatan(param, code, code1){
    var formData = new FormData();
    formData.append('code', code);
    formData.append('code1', code1);

  $.ajax({
      type: 'post',
      processData: false,
      contentType: false,
      url: 'load'+param,
      data : formData,
      success: function(result){

        let data = result.data;
        let code = result.code;
        let el1   = '<option value=""></option>';
        let el2   = '<option value=""></option>';
        let el3   = '<option value=""></option>';

        if(typeof data == 'object'){
          for (var i = 0; i < data.length; i++) {
            
            el1 += '<option value="'+data[i].kode_program+'" text="'+data[i].nama_program+'" selected>'+data[i].kode_program+'</option>';
            el2 += '<option value="'+data[i].kode_kegiatan+'" text="'+data[i].nama_kegiatan+'" selected>'+data[i].kode_kegiatan+'</option>';
            let sisapagudong = '0';
            
            if(typeof(data[i].sisa_pagu_subkegiatan) !== "undefined"){
              
              if(data[i].sisa_pagu_subkegiatan != null ){
                sisapagudong = rubah(data[i].sisa_pagu_subkegiatan);
          
              }else if(data[i].sisa_pagu_subkegiatan == '0'){
                sisapagudong = '0'
              }else{
                sisapagudong = data[i].pagu_subkegiatan
              }
            }
            el3 += '<option pagu_sub="'+sisapagudong+'" value="'+data[i].kode_subkegiatan+'" text="'+data[i].kode_subkegiatan+'" keg="'+data[i].kode_kegiatan+'" prog="'+data[i].kode_program+'">'+data[i].nama_subkegiatan+'</option>';
          }
        }

          if(param == 'program'){
            $('#kode_program').html(el1);
            $('#kode_program').trigger("chosen:updated");
          }else if(param == 'kegiatan'){
            $('#kode_kegiatan').html(el2);
            $('#kode_kegiatan').trigger("chosen:updated");
          }else if(param == 'subkegiatan'){
            $('#nama_subkegiatan').html(el3);
            $('#nama_subkegiatan').trigger("chosen:updated");
          }else if(param == 'paket'){
            console.log(data);
            if(code == 1){
              $('#kodpak_2').val(parseInt(data.length) + 1);
            }else{
              $('#kodpak_2').val(1);
            }
            
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
          title: 'Berhasil Tambah Paket !',
          showConfirmButton: true,
          // showCancelButton: true,
          confirmButtonText: `Ok`,
        }).then((result) => {
          $(document).ready(function(){
              // loadpaket('');
              // $('#kode_program').val(0).trigger("chosen:updated");
              // $('#kode_kegiatan').val(0).trigger("chosen:updated");
              // $('#kode_subkegiatan').val(0).trigger("chosen:updated");
              // $('#nama_paket').val('');
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
        url: 'updatepaket',
        data : formData,
        success: function(result){
          Swal.fire({
            type: 'success',
            title: 'Berhasil Update Paket !',
            showConfirmButton: true,
            // showCancelButton: true,
            confirmButtonText: `Ok`,
          }).then((result) => {
            $(document).ready(function(){
                // loadpaket('');
                // $('#kode_program').val(0).trigger("chosen:updated");
                // $('#kode_kegiatan').val(0).trigger("chosen:updated");
                // $('#kode_subkegiatan').val(0).trigger("chosen:updated");
                // $('#nama_paket').val('');
                location.reload()
  
            });
          })
        }
      });
    };
  

  function action(table, id){
    bootbox.confirm({
      message: "Anda Yakin <b>Hapus</b> Paket ini?",
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
                // loadpaket('');
                //   $('#kode_program').val(0).trigger("chosen:updated");
                //   $('#kode_kegiatan').val(0).trigger("chosen:updated");
                //   $('#kode_subkegiatan').val('');
                //   $('#nama_subkegiatan').val('');
                location.reload()
    
              });
            })
          }
        });
      }
    }
});

};

function rubah(angka){
  var reverse = angka.toString().split('').reverse().join(''),
  ribuan = reverse.match(/\d{1,3}/g);
  ribuan = ribuan.join('.').split('').reverse().join('');
  return ribuan;
}

function edit(table,id, code, name, pagu, code_sub, pagu_perubahan){
  $('#modal_paket').modal('show');
  let mycode = code.split(".");
  $('#nama_subkegiatan').val(code_sub);
  $('#nama_subkegiatan').chosen().change();
  $(".chosen-select").prop('disabled', true);
  $('#nama_subkegiatan').trigger("chosen:updated");
  $('#kodpak_1').val(code.slice(0, -mycode[mycode.length - 1].length));
  $('#kodpak_2').val(mycode[mycode.length - 1]);
  $('#kodpak_2').prop('disabled', true);

  $('#nama_paket').val(name);
  $('#id_paket').val(id);
  $('#pagu_paket').val(pagu);
  $('#pagu_paket').prop('disabled', true);

  $('#pagu_perubahan').val(pagu_perubahan == 'null' ? '': pagu_perubahan);
  $('#pagu_perubahan').parent().parent().show();
  

}
