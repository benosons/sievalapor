"use strict";
console.log('You are running jQuery version: ' + $.fn.jquery);
$(document).ready(function(){
  const ids = $('#ids').val();
  window.roles = $('#role').val();
  window.type = 'keuangan';
  $('#nav-menu li').removeClass();
  $('#nav-menu li#menu-laporan').addClass('active');

  $( '.uang, .uang-pagu' ).mask('000.000.000.000.000', {reverse: true});
  // loadkegiatan("program",0);
  loadtarget(ids);
  // $('#fpilih_bulan_chosen').hide();
  // $('#fprogres_bulan_lalu').hide();
  // $('#ftotal_progres').hide();
  // $('#inifisik').hide();

  // $('#iniprogres').hide();
  //  $('#iniupload').hide();
  //  $('#iniketerangan').hide();

  //  $('#ktotal_progres').val('');
  //  $('#kprogres_bulan_lalu').val('');
  //  $('#koordinat').val('');
  //  $('#latar_belakang').val('');
  //  $('#uraian').val('');
  //  $('#permasalahan').val('');
  //  $('#ktot_prog').val('');

   $('input:checkbox').removeAttr('checked');

   for (let i = 1; i <= 4; i++) {
     $('#kprogres_mingu_'+i).val('');
     
   }

  $('#id-input-file-2-').ace_file_input({
    no_file:'No File ...',
    btn_choose:'Choose',
    btn_change:'Change',
    droppable:false,
    onchange:null,
    thumbnail:false //| true | large
    //whitelist:'gif|png|jpg|jpeg'
    //blacklist:'exe|php'
    //onchange:''
    //
  });

  $('#id-input-file-2').ace_file_input({
    style:'well',
    btn_choose:'Drop files here or click to choose',
    btn_change:null,
    no_icon:'ace-icon fa fa-cloud-upload',
    droppable:true,
    thumbnail:'small'//large | fit
    //,icon_remove:null//set null, to hide remove/reset button
    /**,before_change:function(files, dropped) {
      //Check an example below
      //or examples/file-upload.html
      return true;
    }*/
    /**,before_remove : function() {
      return true;
    }*/
    ,
    preview_error : function(filename, error_code) {
      //name of the file that failed
      //error_code values
      //1 = 'FILE_LOAD_FAILED',
      //2 = 'IMAGE_LOAD_FAILED',
      //3 = 'THUMBNAIL_FAILED'
      //alert(error_code);
    }

  }).on('change', function(){
    //console.log($(this).data('ace_input_files'));
    //console.log($(this).data('ace_input_method'));
  });


//   $('#tipedong').click(function(){
//     $('#koordinat').val('');
//     $('#latar_belakang').val('');
//     $('#uraian').val('');
//     $('#permasalahan').val('');
//     if($(this).prop("checked") == true){
//       window.type = 'fisik';
//       $('#fpilih_bulan_chosen').show();
//       $('#fprogres_bulan_lalu').show();
//       $('#ftotal_progres').show();
//       $('#fpermasalahan').show();
//       $('#inifisik').show();

//       $('#kpilih_bulan_chosen').hide();
//       $('#kprogres_bulan_lalu').hide();
//       $('#ktotal_progres').hide();
//       $('#kpermasalahan').hide();
//       $('#inikeuangan').hide();

//       $('#iniprogres').show();
//       $('#iniupload').show();
//       $('#iniketerangan').show();
//     }else if($(this).prop("checked") == false){
//       window.type = 'keuangan';
//       $('#kpilih_bulan_chosen').show();
//       $('#kprogres_bulan_lalu').show();
//       $('#ktotal_progres').show();
//       $('#kpermasalahan').show();
//       $('#inikeuangan').show();

//       $('#fpilih_bulan_chosen').hide();
//       $('#fprogres_bulan_lalu').hide();
//       $('#ftotal_progres').hide();
//       $('#fpermasalahan').hide();
//       $('#inifisik').hide();

//       $('#iniprogres').hide();
//       $('#iniupload').hide();
//       $('#iniketerangan').hide();
//     }

// });

$('#tipenya-keuangan').on('click', function(){
  window.type = 'keuangan';
});

$('#tipenya-fisik').on('click', function(){
  window.type = 'fisik';
})

  $("#kpilih_bulan").chosen().change(function(){
    let nama = $('option:selected', this).text();
    $('[name="isibulan"]').html(nama);
    loadbulan(window.type, this.value);
  });

  $("#fpilih_bulan").chosen().change(function(){
    let nama = $('option:selected', this).text();
    $('[name="isibulan"]').html(nama);
    loadbulan(window.type, this.value);
  });

  var ktot = [];
  $('.uang').keyup(function(){
    ktot = [];
    for (var i = 1; i <= 4; i++) {
      let vlue = $('#kprogres_mingu_'+i).val();
      let lue = vlue.replaceAll('.', '');
      let vl = ($('#kprogres_mingu_'+i).val() == '') ? 0 : parseInt(lue);
      ktot.push(vl);
    }
    ;
    $('#ktot_prog').val(rubah(ktot.reduce((a, b) => a + b, 0)));
  })

});

function rubah(angka){
 var reverse = angka.toString().split('').reverse().join(''),
 ribuan = reverse.match(/\d{1,3}/g);
 ribuan = ribuan.join('.').split('').reverse().join('');
 return ribuan;
}

function saveminggu(type,ke){

  var formData = new FormData();
  formData.append('type', type);
  let keys;
  switch (type) {
    case 'keuangan':
        keys = 'k';
      break;
    case 'fisik':
        keys = 'f';
      break;
    default:

  }

  formData.append('kode_bulan', $('#'+keys+'pilih_bulan').val());
  formData.append('id_paket', $('#id_paket').val());
  formData.append('m'+ke, $('#'+keys+'progres_mingu_'+ke).val());
  formData.append('total_progres', $('#'+keys+'tot_prog').val());
  formData.append('koordinat', $('#'+keys+'koordinat').val());
  formData.append('latar_belakang', $('#'+keys+'latar_belakang').val());
  formData.append('uraian', $('#'+keys+'uraian').val());
  formData.append('permasalahan', $('#'+keys+'permasalahan').val());

  if($('#'+keys+'edit_'+ke).is(":checked")){
    formData.append('edited', 1);
    formData.append('idnya', $('#'+keys+'edit_'+ke).attr("idnya"));
  }

  if(type == 'fisik'){
    formData.append('progres', $('#fprogres').val());
    formData.append('keterangan', $('#fketerangan').val());
    
    if (typeof $('#id-input-file-2')[0].files[0] != 'undefined') {
      for (let index = 0; index < $('#id-input-file-2')[0].files.length; index++) {
        formData.append('file[]', $('#id-input-file-2')[0].files[index]);
      }
    }
  }

  $.ajax({
      type: 'post',
      processData: false,
      contentType: false,
      url: 'addRealisasi',
      data : formData,
      success: function(result){
        Swal.fire({
          type: 'success',
          title: 'Berhasil Tambah Realisasi Minggu Ke #'+ke+' !',
          showConfirmButton: true,
          // showCancelButton: true,
          confirmButtonText: `Ok`,
        }).then((result) => {
          $(document).ready(function(){
              // loadprogram('');
              // $('#kode_program').val('');
              // $('#nama_program').val('');
              location.reload();

          });
        })
      }
    });
}

    function loadtarget(param){

  $.ajax({
      type: 'post',
      dataType: 'json',
      url: 'loadtarget',
      data : {
              code      : param,
      },
      success: function(result){
          let data = result.data;
          // $('#kode_program').html('<option value="'+data[0].kode_program+'">'+data[0].kode_program+'</option>').trigger("chosen:updated");
          $('#kode_program').val(data[0].kode_program);
          $('#nama_program').val(data[0].nama_program);

          // $('#kode_kegiatan').html('<option value="'+data[0].kode_kegiatan+'">'+data[0].kode_kegiatan+'</option>').trigger("chosen:updated");
          $('#kode_kegiatan').val(data[0].kode_kegiatan);
          $('#nama_kegiatan').val(data[0].nama_kegiatan);

          // $('#kode_subkegiatan').html('<option value="'+data[0].kode_subkegiatan+'">'+data[0].kode_subkegiatan+'</option>').trigger("chosen:updated");
          $('#kode_subkegiatan').val(data[0].kode_subkegiatan);
          $('#nama_subkegiatan').val(data[0].nama_subkegiatan);

          // $('#paket').html('<option value="'+data[0].id_paket+'">'+data[0].nama_paket+'</option>').trigger("chosen:updated");
          $('#paket').val(data[0].nama_paket);
          $('#id_paket').val(data[0].id_paket);
          $('#pagu_kegiatan').val(data[0].pagu);

          for (var i = 0; i < data.length; i++) {
              if(data[i].type == 'keuangan'){
                let databulan = '<option value="0"></option>';

                if(data[i].n1 != ''){
                  databulan += '<option value="n1">Januari</option>';
                }
                if(data[i].n2 != ''){
                  databulan += '<option value="n2">Februari</option>';
                }
                if(data[i].n3 != ''){
                  databulan += '<option value="n3">Maret</option>';
                }
                if(data[i].n4 != ''){
                  databulan += '<option value="n4">April</option>';
                }
                if(data[i].n5 != ''){
                  databulan += '<option value="n5">Mei</option>';
                }
                if(data[i].n6 != ''){
                  databulan += '<option value="n6">Juni</option>';
                }
                if(data[i].n7 != ''){
                  databulan += '<option value="n7">Juli</option>';
                }
                if(data[i].n8 != ''){
                  databulan += '<option value="n8">Agustus</option>';
                }
                if(data[i].n9 != ''){
                  databulan += '<option value="n9">September</option>';
                }
                if(data[i].n10 != ''){
                  databulan += '<option value="n10">Oktober</option>';
                }
                if(data[i].n11 != ''){
                  databulan += '<option value="n11">November</option>';
                }
                if(data[i].n12 != ''){
                  databulan += '<option value="n12">Desember</option>';
                }

                $('#kpilih_bulan').html(databulan);
                $('#kpilih_bulan').trigger("chosen:updated");
              }

              if(data[i].type == 'fisik'){

                let databulan = '<option value="0"></option>';

                if(data[i].n1 != ''){
                  databulan += '<option value="n1">Januari</option>';
                }
                if(data[i].n2 != ''){
                  databulan += '<option value="n2">Februari</option>';
                }
                if(data[i].n3 != ''){
                  databulan += '<option value="n3">Maret</option>';
                }
                if(data[i].n4 != ''){
                  databulan += '<option value="n4">April</option>';
                }
                if(data[i].n5 != ''){
                  databulan += '<option value="n5">Mei</option>';
                }
                if(data[i].n6 != ''){
                  databulan += '<option value="n6">Juni</option>';
                }
                if(data[i].n7 != ''){
                  databulan += '<option value="n7">Juli</option>';
                }
                if(data[i].n8 != ''){
                  databulan += '<option value="n8">Agustus</option>';
                }
                if(data[i].n9 != ''){
                  databulan += '<option value="n9">September</option>';
                }
                if(data[i].n10 != ''){
                  databulan += '<option value="n10">Oktober</option>';
                }
                if(data[i].n11 != ''){
                  databulan += '<option value="n11">November</option>';
                }
                if(data[i].n12 != ''){
                  databulan += '<option value="n12">Desember</option>';
                }

                $('#fpilih_bulan').html(databulan);
                $('#fpilih_bulan').trigger("chosen:updated");

            }
          }
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
            let el3   = '<option value=""></option>';
            let el4   = '<option value=""></option>';

            if(typeof data == 'object'){
              for (var i = 0; i < data.length; i++) {
                el1 += '<option nama="'+data[i].nama_program+'" value="'+data[i].kode_program+'">'+data[i].kode_program+'</option>';
                el2 += '<option nama="'+data[i].nama_kegiatan+'" value="'+data[i].kode_kegiatan+'">'+data[i].kode_kegiatan+'</option>';
                el3 += '<option nama="'+data[i].nama_subkegiatan+'" value="'+data[i].kode_subkegiatan+'">'+data[i].kode_subkegiatan+'</option>';
                el4 += '<option value="'+data[i].id+'">'+data[i].nama_paket+'</option>';
              }
            }

              if(param == 'program'){
                $('#kode_program').html(el1);
                $('#kode_program').trigger("chosen:updated");
                loadkegiatan("kegiatan", code);
              }else if(param == 'kegiatan'){
                $('#kode_kegiatan').html(el2);
                $('#kode_kegiatan').trigger("chosen:updated");
                loadkegiatan("subkegiatan",code);
              }else if(param == 'subkegiatan'){
                $('#kode_subkegiatan').html(el3);
                $('#kode_subkegiatan').trigger("chosen:updated");
                loadkegiatan("paket",code);
              }else if(param == 'paket'){
                $('#paket').html(el4);
                $('#paket').trigger("chosen:updated");
                loadtarget(ids.value);
              }
            }
          })
        }

    function loadbulan(type, param){
      $('#ktot_prog').val('');
      $('#ftot_prog').val('');
      $('#kprogres_bulan_lalu').val('');
      $('#fprogres_bulan_lalu').val('');
      $.ajax({
          type: 'post',
          dataType: 'json',
          url: 'loadminggu',
          data : {
                  type      : type,
                  code      : param,
                  idpaket   : $('#id_paket').val(),
          },
          success: function(result){
              let data = result.data;
            
            if(!Array.isArray(data)){
              for (var i = 1; i <= 4; i++) {
                if(type == 'keuangan'){
                  
                    if($('#ktotal_progres').val()){
                      var reverse11 = $('#ktotal_progres').val().toString().split('').reverse().join(''),
                      ribuan_lalu11 = reverse11.match(/\d{1,3}/g);
                      ribuan_lalu11 = ribuan_lalu11.join('.').split('').reverse().join('');

                      if($('#kprogres_bulan_lalu').val() == ''){
                        $('#kprogres_bulan_lalu').val(ribuan_lalu11);
                      }
                    }

                  $('#kprogres_mingu_'+i).val('');
                  $('#ktotal_progres').val('');
                  $('#kprogres_mingu_'+i).prop('disabled', false);
                  $('#ksave_minggu_'+i).prop('disabled', false);
                  $('#kkoordinat').val('');
                  $('#klatar_belakang').val('');
                  $('#kuraian').val('');
                  $('#kpermasalahan').val('');

                  $('#kkoordinat').prop('disabled', false);
                  $('#klatar_belakang').prop('disabled', false);
                  $('#kuraian').prop('disabled', false);
                  $('#kpermasalahan').prop('disabled', false);


                }else if(type = 'fisik'){
                  $('#fprogres_mingu_'+i).val('');
                  $('#ftotal_progres').val('');
                  $('#fprogres_mingu_'+i).prop('disabled', false);
                  $('#fsave_minggu_'+i).prop('disabled', false);
                  $('#fkoordinat').val('');
                  $('#flatar_belakang').val('');
                  $('#furaian').val('');
                  $('#fpermasalahan').val('');

                  $('#fkoordinat').prop('disabled', false);
                  $('#flatar_belakang').prop('disabled', false);
                  $('#furaian').prop('disabled', false);
                  $('#fpermasalahan').prop('disabled', false);

                }

              }

            }

            for (var i = 0; i < data.length; i++) {
              if(data[i].type == 'keuangan'){
                
                let totok = [];
                if(typeof data[i] !== 'undefined'){
                  if(data[i].totalnya){
                    var totnya = data[i].totalnya.toString().split('').reverse().join(''),
                    ribuan_tot = totnya.match(/\d{1,3}/g);
                    ribuan_tot = ribuan_tot.join('.').split('').reverse().join('');
                  }else{
                  var ribuan_tot = null;
                  }

                  $('#ktotal_progres').val(ribuan_tot);
                  
                  if(data[i].koordinat){
                    $('#kkoordinat').val(data[i].koordinat);
                    $('#kkoordinat').prop('disabled', true);
                  }
                  if(data[i].latar_belakang){
                    $('#klatar_belakang').val(data[i].latar_belakang);
                    $('#klatar_belakang').prop('disabled', true);
                  }
                  if(data[i].uraian){
                    $('#kuraian').val(data[i].uraian);
                    $('#kuraian').prop('disabled', true);
                  }
                  
                  if(data[i].permasalahan){
                    $('#kpermasalahan').val(data[i].permasalahan);
                    $('#kpermasalahan').prop('disabled', true);
                  }else{
                    $('#kpermasalahan').val('');
                    $('#kpermasalahan').prop('disabled', false);
                  }


                  for (var ii = 1; ii <= 4; ii++) {
                    if(data[i]['m'+ii]){
                      var em = data[i]['m'+ii].toString().split('').reverse().join(''),
                      ribuan = em.match(/\d{1,3}/g);
                      ribuan = ribuan.join('.').split('').reverse().join('');
                      $('#kprogres_mingu_'+ii).val(ribuan);
                      $('#kprogres_mingu_'+ii).prop('disabled', true);
                      $('#ksave_minggu_'+ii).prop('disabled', true);
                    }else{
                      $('#kprogres_mingu_'+ii).prop('disabled', false);
                      $('#ksave_minggu_'+ii).prop('disabled', false);
                      $('#kprogres_mingu_'+ii).val('');
                    }
                  }

                  $('#kprogres_bulan_lalu').val(data[i].total_sebelumnya);


                  // $('#kedit_1').attr('idnya',data[i].id);
                  // $('#kedit_2').attr('idnya',data[i].id);
                  // $('#kedit_3').attr('idnya',data[i].id);
                  // $('#kedit_4').attr('idnya',data[i].id);
                }

                let angkanya_bulan_lalu = $('#kprogres_bulan_lalu').val();
                let angkanya_progres = $('#ktotal_progres').val();
                var ribuan_lalu;
                if(angkanya_bulan_lalu){
                  var reverse = angkanya_bulan_lalu.toString().split('').reverse().join(''),
                  ribuan_lalu = reverse.match(/\d{1,3}/g);
                  ribuan_lalu = ribuan_lalu.join('.').split('').reverse().join('');
                }

                if(angkanya_progres){
                  var reverse1 = angkanya_progres.toString().split('').reverse().join(''),
                  ribuan = reverse1.match(/\d{1,3}/g);
                  ribuan = ribuan.join('.').split('').reverse().join('');
                }else{
                  var ribuan = null;
                }

                $('#kprogres_bulan_lalu').val(ribuan_lalu);
                $('#ktot_prog').val(ribuan);

              }else if(data[i].type == 'fisik'){

                if(typeof data[i] !== 'undefined'){

                  $('#ftotal_progres').val(data[i].total);
                  if(data[i].koordinat){
                    $('#fkoordinat').val(data[i].koordinat);
                    $('#fkoordinat').prop('disabled', true);
                  }
                  if(data[i].latar_belakang){
                    $('#flatar_belakang').val(data[i].latar_belakang);
                    $('#flatar_belakang').prop('disabled', true);
                  }
                  if(data[i].uraian){
                    $('#furaian').val(data[i].uraian);
                    $('#furaian').prop('disabled', true);
                    
                  }else{
                    $('#furaian').val('');
                    $('#furaian').prop('disabled', false);
                  }
                  
                  if(data[i].permasalahan){
                    $('#fpermasalahan').val(data[i].permasalahan);
                    $('#fpermasalahan').prop('disabled', true);
                  }else{
                    $('#fpermasalahan').val('');
                    $('#fpermasalahan').prop('disabled', false);
                  }


                  for (var ii = 1; ii <= 4; ii++) {
                    if(data[i]['m'+ii]){
                      $('#fprogres_mingu_'+ii).val(data[i]['m'+ii]);
                      $('#fprogres_mingu_'+ii).prop('disabled', true);
                      $('#fsave_minggu_'+ii).prop('disabled', true);
                    }else{
                      $('#fprogres_mingu_'+ii).prop('disabled', false);
                      $('#fsave_minggu_'+ii).prop('disabled', false);
                      $('#fprogres_mingu_'+ii).val('');
                    }
                  }

                  $('#fprogres_bulan_lalu').val(data[i].total_sebelumnya);


                  // $('#kedit_1').attr('idnya',data[i].id);
                  // $('#kedit_2').attr('idnya',data[i].id);
                  // $('#kedit_3').attr('idnya',data[i].id);
                  // $('#kedit_4').attr('idnya',data[i].id);
                  if(data[i].progres){
                   
                    $('#fprogres_input').val(data[i].progres);
                    $('#fketerangan').val(data[i].keterangan);
                    $('#fketerangan').prop('disabled', true);

                    // $('#img-file').attr('href',);
                    // $('#img-file > img').attr('src',);
                    let imag = `
                                  <div class="col-xs-12">
                                    <div>
                                      <ul class="ace-thumbnails clearfix">`;
                    
                    for (let index = 0; index < data[i]['file'].length; index++) {
                      
                      imag += `<li>
											<a target="_blank" href="`+$('#baseURL').val() +'/'+ data[i]['file'][index]['path']+data[i]['file'][index]['filename']+`" title="Photo Title" data-rel="colorbox">
												<img width="150" height="150" alt="150x150" src="`+$('#baseURL').val() +'/'+ data[i]['file'][index]['path']+data[i]['file'][index]['filename']+`" />
											</a>
										  </li>`
                    }

                    imag += '</ul></div></div>';
                    

                    $('#img-file').html(imag);

                    $('#fprogres_chosen').hide();
                    $('.ace-file-input').hide();
                    $('#img-file').show();
                    $('#progres_input').show();
                  }else{
                    $('#fprogres_chosen').show();
                    $('.ace-file-input').show();
                    $('#img-file').hide();
                    $('#fprogres_input').hide();
                    $('#fketerangan').val('');
                    $('#fketerangan').prop('disabled', false);
                  }
                  
                  

                  
                }

                }
              }

            }
          });
        }

  function editdong(type, ke){
    switch (window.type) {
      case 'fisik':
          var kunci = 'f';
        break;
      default:
        var kunci = 'k';
    }

    if($('#'+kunci+'progres_mingu_'+ke).val()){
      if($('#'+kunci+'edit_'+ke).is(":checked")){
        $('#'+kunci+'progres_mingu_'+ke).prop('disabled', false);
        $('#'+kunci+'save_minggu_'+ke).prop('disabled', false);
      }else{
        $('#'+kunci+'progres_mingu_'+ke).prop('disabled', true);
        $('#'+kunci+'save_minggu_'+ke).prop('disabled', true);
      }
    }

  }
