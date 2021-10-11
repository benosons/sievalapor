"use strict";
console.log('You are running jQuery version: ' + $.fn.jquery);
$(document).ready(function(){
  const ids = $('#ids').val();
  $('#nav-menu li').removeClass();
  // $('#nav-menu li#menu-rencana').addClass('active');
  $('#nav-menu li#menu-data').addClass('open');
  $('#nav-menu li#menu-paket').addClass('active');
  $( '.uang, .uang-pagu' ).mask('000.000.000.000.000', {reverse: true});
  
  // loadkegiatan("program",0);
  // loadppk();

  loadpaketnya("paket",ids);

  $('#save_target').on('click', function(){
      var kode_program = $('#kode_program').val();
      var kode_kegiatan = $('#kode_kegiatan').val();
      var kode_subkegiatan = $('#kode_subkegiatan').val();
      var id_paket = ids;
      var pagu_kegiatan = $('#pagu_kegiatan').val();
      var ktot = $('#ktot').val();
      var ftot = $('#ftot').val();
      // var ppk = $('#pilih_ppk').val();
      var bidang = $('#pilih_bidang').val();
      var seksi = $('#pilih_seksi').val();
      var target_output = $('#target_output').val();
      var satuan = $('#satuan').val();

      var formData = new FormData();
      formData.append('param', 'data_target');
      formData.append('kode_program', kode_program);
      formData.append('kode_kegiatan', kode_kegiatan);
      formData.append('kode_subkegiatan', kode_subkegiatan);
      formData.append('id_paket', id_paket);
      formData.append('pagu_kegiatan', pagu_kegiatan);
      formData.append('ktot', ktot);
      formData.append('ftot', ftot);
      // formData.append('ppk', ppk);
      formData.append('bidang', bidang);
      formData.append('seksi', seksi);
      formData.append('target_output', target_output);
      formData.append('satuan', satuan);

        for (var i = 1; i <= 12; i++) {
          formData.append('k'+i, $('#k'+i).val());
          formData.append('kp'+i, $('#kp'+i).val());
          formData.append('f'+i, $('#f'+i).val());
        }

      save(formData);
  });

  $("#kode_program").chosen().change(function(){
    let nama = $('option:selected', this).attr('value');
    $('#nama_program').val('');
    $('#nama_kegiatan').val('');
    $('#nama_subkegiatan').val('');

    $('#kode_kegiatan').html('<option value=""></option>').trigger("chosen:updated");
    $('#kode_subkegiatan').html('<option value=""></option>').trigger("chosen:updated");

    $('#nama_program').val(nama);


    loadkegiatan("kegiatan",this.value);
  });

  $("#kode_kegiatan").chosen().change(function(){
    let nama = $('option:selected', this).attr('value');
    $('#nama_kegiatan').val('');
    $('#nama_subkegiatan').val('');

    $('#kode_subkegiatan').html('<option value=""></option>').trigger("chosen:updated");

    $('#nama_kegiatan').val(nama);
    loadkegiatan("subkegiatan",this.value);
  });

  $("#kode_subkegiatan").chosen().change(function(){
    let nama = $('option:selected', this).attr('value');
    $('#nama_subkegiatan').val('');
    $('#nama_subkegiatan').val(nama);
    loadkegiatan("paket",this.value);
  });

  $("#paket").chosen().change(function(){
    let nama = $('option:selected', this).attr('kode');
    let pagu = $('option:selected', this).attr('pagunya');
    
    $('#kode_paket').val('');
    $('#kode_paket').val(nama);
    $('#pagu_kegiatan').val(pagu);
    $('#pagu_kegiatan').prop('disabled', true);
  });

  $('#pilih_bidang').on('change', function(){
    var el = '<option value="">  </option>'
    switch (this.value) {
      case 'Infrastruktur Permukiman':
            el += `<option value="Drainase dan Air Limbah">Drainase dan Air Limbah</option>`
            el += `<option value="Air Minum">Air Minum</option>`
            el += `<option value="Persampahan">Persampahan</option>`;

        break;
      case 'Perumahan':
            el += `<option value="Penyelengaraan Bangunan Gedung" >Penyelengaraan Bangunan Gedung</option>`;
            el += `<option value="Rumah Khusus dan Swadaya" >Rumah Khusus dan Swadaya</option>`;
            el += `<option value="Rumah Umum" >Rumah Umum</option>`;
        break;
      case 'Kawasan Permukiman':
            el += '<option value="Penetaan Kawasan Permukian Perkotaan" >Penetaan Kawasan Permukian Perkotaan</option>';
            el += '<option value="Pedesaan" >Pedesaan</option>';
        break;
      case 'Pertanahan':
            el += '<option value="Perencanaan Pengadaan tanah">Perencanaan Pengadaan tanah</option>';
            el += '<option value="Data dan Informasi">Data dan Informasi</option>';
            el += '<option value="Penataan Gunaan tanah">Penataan Gunaan tanah</option>';
        break;
      case 'Sekretariat':
            el += '<option value="Subag PP">Subag PP</option>';
            el += '<option value="Kepegawaian dan Umum">Kepegawaian dan Umum</option>';
            el += '<option value="Gaji dan Keuangan">Gaji dan Keuangan</option>';
        break;
      case 'UPT P3JB':
            el += '<option value="Pengelolaan Rusunawa">Pengelolaan Rusunawa</option>';
        break;
      default:

    }

    $('#pilih_seksi').html(el).trigger("chosen:updated");
  })


  var ktot = [];
  $('.uang').keyup(function(){
    ktot = [];
    pertot = [];
    for (var i = 1; i <= 12; i++) {
      
      let vlue = $('#k'+i).val();
      let lue = vlue.replaceAll('.', '');
      let vl = ($('#k'+i).val() == '') ? 0 : parseInt(lue);
      if(vl == 0){
        $('#k'+i).attr('placeholder', '0');
      }

      if(vl != 0){
        ktot.push(vl);
      }
      if($('#pagu_kegiatan').val()){
        let pagu = $('#pagu_kegiatan').val().replaceAll('.', '');
        let persen = (vl / pagu) * 100;
        $('#kp'+i).val(persen.toFixed(2) + '%');
        if(persen != 0){
          pertot.push(persen);
        }
        
      }
    }
    
    $('#ktot').val(rubah(ktot[ktot.length - 1]));
    // $('#ktot').val(rubah(ktot.reduce((a, b) => a + b, 0)));
    $('#pertot').val(pertot[pertot.length - 1] + '%');
    // $('#pertot').val(rubah(pertot.reduce((a, b) => a + b, 0)) + '%');

    if(parseInt($('#ktot').val().replaceAll('.', '')) > parseInt($('#pagu_kegiatan').val().replaceAll('.', ''))){
      Swal.fire({
        type: 'warning',
        title: 'Total sudah Melebihi Pagu',
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

      $('#save_target').prop('disabled', true)
    }else{
      $('#save_target').prop('disabled', false)
    }
  })

  let ftot = [];
  $('.fis').keyup(function(){
    ftot = [];
    for (var i = 1; i <= 12; i++) {
      let vlue = $('#f'+i).val();
      if(vlue){
        ftot.push(vlue);
      }
    }
    $('#ftot').val(ftot[ftot.length - 1]);
  });

  function rubah(angka){
   var reverse = angka.toString().split('').reverse().join(''),
   ribuan = reverse.match(/\d{1,3}/g);
   ribuan = ribuan.join('.').split('').reverse().join('');
   return ribuan;
 }


});

function save(formData){

  $.ajax({
      type: 'post',
      processData: false,
      contentType: false,
      url: 'addTarget',
      data : formData,
      success: function(result){
        Swal.fire({
          type: 'success',
          title: 'Berhasil Tambah Target !',
          showConfirmButton: true,
          // showCancelButton: true,
          confirmButtonText: `Ok`,
        }).then((result) => {
          $(document).ready(function(){
              // loadprogram('');
              // $('#kode_program').val('');
              // $('#nama_program').val('');
              // location.reload()

              window.location.href = $('#baseURL').val().replace('public', 'paket');

          });
        })
      }
    });
  };

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
              el1 += '<option nama="'+data[i].nama_program+'" value="'+data[i].kode_program+'" text="'+data[i].nama_program+'">'+data[i].nama_program+'</option>';
              el2 += '<option nama="'+data[i].nama_kegiatan+'" value="'+data[i].kode_kegiatan+'" text="'+data[i].nama_kegiatan+'">'+data[i].nama_kegiatan+'</option>';
              el3 += '<option nama="'+data[i].nama_subkegiatan+'" value="'+data[i].kode_subkegiatan+'" text="'+data[i].nama_subkegiatan+'">'+data[i].nama_subkegiatan+'</option>';
              var pagu_paket = '';
              if(param == 'paket'){
                pagu_paket = (data[i].pagu_perubahan == '' || data[i].pagu_perubahan == null) ? data[i].pagu_paket : data[i].pagu_perubahan;
              }
              el4 += '<option value="'+data[i].id+'" kode="'+data[i].kode_paket+'" pagunya="'+pagu_paket+'">'+data[i].nama_paket+'</option>';
            }
          }

            if(param == 'program'){
              $('#kode_program').html(el1);
              $('#kode_program').trigger("chosen:updated");
            }else if(param == 'kegiatan'){
              $('#kode_kegiatan').html(el2);
              $('#kode_kegiatan').trigger("chosen:updated");
            }else if(param == 'subkegiatan'){
              $('#kode_subkegiatan').html(el3);
              $('#kode_subkegiatan').trigger("chosen:updated");
            }else if(param == 'paket'){
              $('#paket').html(el4);
              $('#paket').trigger("chosen:updated");
            }
          }
        })
      }
  function loadppk(param, code){
      var formData = new FormData();
      formData.append('code', code);

    $.ajax({
        type: 'post',
        processData: false,
        contentType: false,
        url: 'loadppk',
        data : formData,
        success: function(result){

          let data = result.data;
          let elppk   = '<option value=""></option>';

            if(typeof data == 'object'){
              for (var i = 0; i < data.length; i++) {
                  elppk += '<option value="'+data[i].user_id+'">'+data[i].user_fullname+'</option>';
              }
            }

          $('#pilih_ppk').html(elppk).trigger('chosen:updated');
          }
        })
      }

      function loadpaketnya(param, ids){
        var formData = new FormData();
      formData.append('ids', ids);

      $.ajax({
        type: 'post',
        processData: false,
        contentType: false,
        url: 'loadpaketnya',
        data : formData,
        success: function(result){

          let data = result.data;
          // data[0]['kode_program']
          // data[0]['kode_kegiatan']
          // data[0]['kode_subkegiatan']
          // data[0]['nama_paket']
          $('#kode_program').html('<option>'+data[0]['kode_program']+'</option>').trigger("chosen:updated");
          $('#kode_kegiatan').html('<option>'+data[0]['kode_kegiatan']+'</option>').trigger("chosen:updated");
          $('#kode_subkegiatan').html('<option>'+data[0]['kode_subkegiatan']+'</option>').trigger("chosen:updated");
          $('#paket').html('<option value="'+data[0]['kode_paket']+'">'+data[0]['nama_paket']+'</option>').prop('disabled', true).trigger("chosen:updated");
          $('#kode_paket').val(data[0]['kode_paket']);
          $('#pagu_kegiatan').val(data[0]['pagu_paket']);
          $('#pagu_kegiatan').prop('disabled', true);
          // data[0]['kode_paket']
          // data[0]['pagu_paket']

          // var kode_program = $('#kode_program').val();
          // var kode_kegiatan = $('#kode_kegiatan').val();
          // var kode_subkegiatan = $('#kode_subkegiatan').val();
          // var id_paket = $('#paket').val();
          // var pagu_kegiatan = $('#pagu_kegiatan').val();

          }
        })
      }
