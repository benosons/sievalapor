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
  $('#fpilih_bulan_chosen').hide();
  $('#fprogres_bulan_lalu').hide();
  $('#ftotal_progres').hide();
  $('#inifisik').hide();


  $('#tipedong').click(function(){
    if($(this).prop("checked") == true){
      window.type = 'fisik';
      $('#fpilih_bulan_chosen').show();
      $('#fprogres_bulan_lalu').show();
      $('#ftotal_progres').show();
      $('#inifisik').show();

      $('#kpilih_bulan_chosen').hide();
      $('#kprogres_bulan_lalu').hide();
      $('#ktotal_progres').hide();
      $('#inikeuangan').hide();
    }else if($(this).prop("checked") == false){
      window.type = 'keuangan';
      $('#kpilih_bulan_chosen').show();
      $('#kprogres_bulan_lalu').show();
      $('#ktotal_progres').show();
      $('#inikeuangan').show();

      $('#fpilih_bulan_chosen').hide();
      $('#fprogres_bulan_lalu').hide();
      $('#ftotal_progres').hide();
      $('#inifisik').hide();
    }

});

  $("#kpilih_bulan, #fpilih_bulan").chosen().change(function(){
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
  if($('#'+keys+'edit_'+ke).is(":checked")){
    formData.append('edited', 1);
    formData.append('idnya', $('#'+keys+'edit_'+ke).attr("idnya"));
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
      $.ajax({
          type: 'post',
          dataType: 'json',
          url: 'loadminggu',
          data : {
                  type      : type,
                  code      : param,
          },
          success: function(result){
              let data = result.data;

            if(!Array.isArray(data)){
              for (var i = 1; i <= 4; i++) {
                if(type == 'keuangan'){
                  $('#kprogres_mingu_'+i).val('');
                  $('#ktotal_progres').val('');
                  $('#kprogres_mingu_'+i).prop('disabled', false);
                  $('#ksave_minggu_'+i).prop('disabled', false);

                }else if(type = 'fisik'){
                  $('#fprogres_mingu_'+i).val('');
                  $('#ftotal_progres').val('');
                  $('#fprogres_mingu_'+i).prop('disabled', false);
                  $('#fsave_minggu_'+i).prop('disabled', false);
                }

              }

            }

            for (var i = 0; i < data.length; i++) {
              if(data[i].type == 'keuangan'){
                let totok = [];
                if(typeof data[0] !== 'undefined'){
                  $('#kprogres_mingu_1').val(data[0].m1);
                  $('#kedit_1').attr('idnya',data[0].id);
                  $('#kprogres_bulan_lalu').val(data[i].total_sebelumnya);
                  $('#ktotal_progres').val(data[0].totalnya);
                }else{
                  $('#kprogres_mingu_1').val('');
                  $('#kprogres_mingu_1').prop('disabled', false);
                  $('#ksave_minggu_1').prop('disabled', false);
                }

                if(typeof data[1] !== 'undefined'){
                  $('#kprogres_mingu_2').val(data[1].m2);
                  $('#kedit_2').attr('idnya',data[1].id);
                  $('#kprogres_bulan_lalu').val(data[1].total_sebelumnya);
                  $('#ktotal_progres').val(data[i].totalnya);
                }else{
                  $('#kprogres_mingu_2').val('');
                  $('#kprogres_mingu_2').prop('disabled', false);
                  $('#ksave_minggu_2').prop('disabled', false);
                }

                if(typeof data[2] !== 'undefined'){
                  $('#kprogres_mingu_3').val(data[2].m3);
                  $('#kedit_3').attr('idnya',data[2].id);
                  $('#kprogres_bulan_lalu').val(data[2].total_sebelumnya);
                  $('#ktotal_progres').val(data[i].totalnya);
                }else{
                  $('#kprogres_mingu_3').val('');
                  $('#kprogres_mingu_3').prop('disabled', false);
                  $('#ksave_minggu_3').prop('disabled', false);
                }

                if(typeof data[3] !== 'undefined'){
                  $('#kprogres_mingu_4').val(data[3].m4);
                  $('#kedit_4').attr('idnya',data[3].id);
                  $('#kprogres_bulan_lalu').val(data[3].total_sebelumnya);
                  $('#ktotal_progres').val(data[i].totalnya);
                }else{
                  $('#kprogres_mingu_4').val('');
                  $('#kprogres_mingu_4').prop('disabled', false);
                  $('#ksave_minggu_4').prop('disabled', false);
                }

                if(typeof data[0] !== 'undefined'){
                  if(data[0].m1){
                    $('#kprogres_mingu_1').prop('disabled', true);
                    $('#ksave_minggu_1').prop('disabled', true);
                  }else{
                    $('#kprogres_mingu_1').prop('disabled', false);
                    $('#ksave_minggu_1').prop('disabled', false);
                  }
                }else{
                  $('#kprogres_mingu_1').val('');
                }

                if(typeof data[1] !== 'undefined'){
                  if(data[1].m2){
                    $('#kprogres_mingu_2').prop('disabled', true);
                    $('#ksave_minggu_2').prop('disabled', true);
                  }else{
                    $('#kprogres_mingu_2').prop('disabled', false);
                    $('#ksave_minggu_2').prop('disabled', false);
                  }
                }else{
                  $('#kprogres_mingu_2').val('');
                }

                if(typeof data[2] !== 'undefined'){
                  if(data[2].m3){
                    $('#kprogres_mingu_3').prop('disabled', true);
                    $('#ksave_minggu_3').prop('disabled', true);
                  }else{
                    $('#kprogres_mingu_3').prop('disabled', false);
                    $('#ksave_minggu_3').prop('disabled', false);
                  }
                }else{
                  $('#kprogres_mingu_3').val('');
                }

                if(typeof data[3] !== 'undefined'){
                  if(data[3].m4){
                    $('#kprogres_mingu_4').prop('disabled', true);
                    $('#ksave_minggu_4').prop('disabled', true);
                  }else{
                    $('#kprogres_mingu_4').prop('disabled', false);
                    $('#ksave_minggu_4').prop('disabled', false);
                  }
                }else{
                  $('#kprogres_mingu_4').val('');
                }
                let angkanya_bulan_lalu = $('#kprogres_bulan_lalu').val();
                let angkanya_progres = $('#ktotal_progres').val();
                var ribuan_lalu;
                if(angkanya_bulan_lalu){
                  var reverse = angkanya_bulan_lalu.toString().split('').reverse().join(''),
                  ribuan_lalu = reverse.match(/\d{1,3}/g);
                  ribuan_lalu = ribuan_lalu.join('.').split('').reverse().join('');
                }

                var reverse1 = angkanya_progres.toString().split('').reverse().join(''),
                ribuan = reverse1.match(/\d{1,3}/g);
                ribuan = ribuan.join('.').split('').reverse().join('');

                $('#kprogres_bulan_lalu').val(ribuan_lalu);
                $('#ktot_prog').val(ribuan);

              }else if(data[i].type == 'fisik'){

                if(typeof data[0] !== 'undefined'){
                  $('#fprogres_mingu_1').val(data[0].m1);
                  $('#fedit_1').attr('idnya',data[0].id);
                  $('#ftotal_progres').val(data[0].m1);
                  $('#fprogres_bulan_lalu').val(data[0].total_sebelumnya);
                }else{
                  $('#fprogres_mingu_1').val('');
                  $('#fprogres_mingu_1').prop('disabled', false);
                  $('#fsave_minggu_1').prop('disabled', false);
                }

                if(typeof data[1] !== 'undefined'){
                  $('#fprogres_mingu_2').val(data[1].m2);
                  $('#fedit_2').attr('idnya',data[1].id);
                  $('#ftotal_progres').val(data[1].m2);
                  $('#fprogres_bulan_lalu').val(data[1].total_sebelumnya);
                }else{
                  $('#fprogres_mingu_2').val('');
                  $('#fprogres_mingu_2').prop('disabled', false);
                  $('#fsave_minggu_2').prop('disabled', false);
                }

                if(typeof data[2] !== 'undefined'){
                  $('#fprogres_mingu_3').val(data[2].m3);
                  $('#fedit_3').attr('idnya',data[2].id);
                  $('#ftotal_progres').val(data[2].m3);
                  $('#fprogres_bulan_lalu').val(data[2].total_sebelumnya);
                }else{
                  $('#fprogres_mingu_3').val('');
                  $('#fprogres_mingu_3').prop('disabled', false);
                  $('#fsave_minggu_3').prop('disabled', false);
                }

                if(typeof data[3] !== 'undefined'){
                  $('#fprogres_mingu_4').val(data[3].m4);
                  $('#fedit_4').attr('idnya',data[3].id);
                  $('#ftotal_progres').val(data[3].m4);
                  $('#fprogres_bulan_lalu').val(data[3].total_sebelumnya);
                }else{
                  $('#ffprogres_mingu_4').val('');
                  $('#ffprogres_mingu_4').prop('disabled', false);
                  $('#ffsave_minggu_4').prop('disabled', false);
                }

                  if(typeof data[0] !== 'undefined'){
                    if(data[0].m1){
                      $('#fprogres_mingu_1').prop('disabled', true);
                      $('#fsave_minggu_1').prop('disabled', true);
                    }else{
                      $('#fprogres_mingu_1').prop('disabled', false);
                      $('#fsave_minggu_1').prop('disabled', false);
                    }
                  }else{
                    $('#fprogres_mingu_1').val('');
                  }

                  if(typeof data[1] !== 'undefined'){
                    if(data[1].m2){
                      $('#fprogres_mingu_2').prop('disabled', true);
                      $('#fsave_minggu_2').prop('disabled', true);
                    }else{
                      $('#fprogres_mingu_2').prop('disabled', false);
                      $('#fsave_minggu_2').prop('disabled', false);
                    }
                  }else{
                    $('#fprogres_mingu_2').val('');
                  }

                  if(typeof data[2] !== 'undefined'){
                    if(data[2].m3){
                      $('#fprogres_mingu_3').prop('disabled', true);
                      $('#fsave_minggu_3').prop('disabled', true);
                    }else{
                      $('#fprogres_mingu_3').prop('disabled', false);
                      $('#fsave_minggu_3').prop('disabled', false);
                    }
                  }else{
                    $('#fprogres_mingu_3').val('');
                  }

                  if(typeof data[3] !== 'undefined'){
                    if(data[3].m4){
                      $('#fprogres_mingu_4').prop('disabled', true);
                      $('#fsave_minggu_4').prop('disabled', true);
                    }else{
                      $('#fprogres_mingu_4').prop('disabled', false);
                      $('#fsave_minggu_4').prop('disabled', false);
                    }
                  }else{
                    $('#fprogres_mingu_4').val('');
                  }

                }
              }

            }
          });
        }

  function editdong(type, ke){
    if($('#progres_mingu_'+ke).val()){
      if($('#edit_'+ke).is(":checked")){
        $('#progres_mingu_'+ke).prop('disabled', false);
        $('#save_minggu_'+ke).prop('disabled', false);
      }else{
        $('#progres_mingu_'+ke).prop('disabled', true);
        $('#save_minggu_'+ke).prop('disabled', true);
      }
    }

  }
