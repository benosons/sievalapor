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
  loadtarget(ids);

  var ktot = [];
  var pertot = [];
  $('.uang').keyup(function(){
    ktot = [];
    pertot = [];
    for (var i = 1; i <= 12; i++) {
      
      let vlue = $('#k'+i).val();
      let lue = vlue.replaceAll('.', '');
      let vl = ($('#k'+i).val() == '') ? 0 : parseInt(lue);
      if(vl == 0){
        $('#k'+i).attr('placeholder', '0');
      }else{
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

    // console.log(ktot[ktot.length - 1]);
    // $('#ktot').val(rubah(ktot.reduce((a, b) => a + b, 0)));
    $('#ktot').val(rubah(ktot[ktot.length - 1]));
    // $('#pertot').val(rubah(pertot.reduce((a, b) => a + b, 0)) + '%');
    $('#pertot').val(pertot[pertot.length - 1] + '%');

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

 $('#save_target').on('click', function(){
  var id_paket = $('#id_paket').val();
  var pagu_kegiatan = $('#pagu_kegiatan').val();
  var ktot = $('#ktot').val();
  var ftot = $('#ftot').val();

  var formData = new FormData();
  formData.append('id', ids);
  formData.append('param', 'data_target');
  formData.append('id_paket', id_paket);
  formData.append('pagu_kegiatan', pagu_kegiatan);
  formData.append('ktot', ktot);
  formData.append('ftot', ftot);

  for (var i = 1; i <= 12; i++) {
    formData.append('k'+i, $('#k'+i).val());
    formData.append('kp'+i, $('#kp'+i).val());
    formData.append('f'+i, $('#f'+i).val());
  }
  
  update(formData);
});

});

function loadtarget(param){
  $('#save_target').hide();
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
          $('#ppk').val(data[0].nama_ppk);
          $('#bidang').val(data[0].bidang);
          $('#seksi').val(data[0].seksi);
          $('#target_output').val(data[0].target_output);
          $('#satuan').val(data[0].satuan);

          // $('#paket').html('<option value="'+data[0].id_paket+'">'+data[0].nama_paket+'</option>').trigger("chosen:updated");
          $('#paket').val(data[0].nama_paket);
          $('#id_paket').val(data[0].id_paket_dt);
          $('#pagu_kegiatan').val(data[0].pagu);


          if(data[0].pagu_perubahan){
            $('#form-pagu-perubahan').show();
            $('#pagu_perubahan').prop('disabled', true);
            $('#pagu_perubahan').val(data[0].pagu_perubahan);
          }

          if(data[0].bulan_perubahan){
            $('#form-bulan-perubahan-view').show();
            $('#bulan_perubahan_view').prop('disabled', true);
            let inibulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'sepetember', 'Oktober', 'November', 'Desember']
            $('#bulan_perubahan_view').val(inibulan[data[0].bulan_perubahan.replace(/n/g, "") - 1]);
          }


        if($('#role').val() != 10){
          $('#save_target').show();
          for (var i = 1; i <= 12; i++) {
            $('#k'+i).prop('disabled', false);
            $('#f'+i).prop('disabled', false);
          }

          for (var i = 0; i < data.length; i++) {
            if(data[i].type == 'keuangan'){
              $('#k1').val(data[i].n1);
              $('#k2').val(data[i].n2);
              $('#k3').val(data[i].n3);
              $('#k4').val(data[i].n4);
              $('#k5').val(data[i].n5);
              $('#k6').val(data[i].n6);
              $('#k7').val(data[i].n7);
              $('#k8').val(data[i].n8);
              $('#k9').val(data[i].n9);
              $('#k10').val(data[i].n10);
              $('#k11').val(data[i].n11);
              $('#k12').val(data[i].n12);
              $('#ktot').val(data[i].tot);

              // var indexterakhir = []; 

              for (let indi = 1; indi <= 12; indi++) {
                var element = data[i]['n'+indi];
                if(element.length == 0){
                  $('#k'+indi).val(0);
                  // indexterakhir.push(element);
                }
              }

              // var lasting = indexterakhir[indexterakhir.length - 1];
              // for (let inde = indexterakhir.length + 1; inde < 13; inde++) {
              //   $('#k'+inde).val(lasting);
                
              // }
              
            }else if(data[i].type == 'fisik'){
              $('#f1').val(data[i].n1);
              $('#f2').val(data[i].n2);
              $('#f3').val(data[i].n3);
              $('#f4').val(data[i].n4);
              $('#f5').val(data[i].n5);
              $('#f6').val(data[i].n6);
              $('#f7').val(data[i].n7);
              $('#f8').val(data[i].n8);
              $('#f9').val(data[i].n9);
              $('#f10').val(data[i].n10);
              $('#f11').val(data[i].n11);
              $('#f12').val(data[i].n12);
              $('#ftot').val(data[i].tot)
            }else if(data[i].type == 'persen'){
              $('#kp1').val(data[i].n1);
              $('#kp2').val(data[i].n2);
              $('#kp3').val(data[i].n3);
              $('#kp4').val(data[i].n4);
              $('#kp5').val(data[i].n5);
              $('#kp6').val(data[i].n6);
              $('#kp7').val(data[i].n7);
              $('#kp8').val(data[i].n8);
              $('#kp9').val(data[i].n9);
              $('#kp10').val(data[i].n10);
              $('#kp11').val(data[i].n11);
              $('#kp12').val(data[i].n12);
              let totah = [];
              for (let ind = 1; ind < 12; ind++) {
                if(data[i]['n'+ind] != '0.00%'){
                  totah.push(data[i]['n'+ind]);
                }
              }
              $('#pertot').val(totah[totah.length - 1]);
            }            
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
                      location.reload();
        
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
                url: 'updateTarget',
                data : formData,
                success: function(result){
                  Swal.fire({
                    type: 'success',
                    title: 'Berhasil Update Target !',
                    showConfirmButton: true,
                    // showCancelButton: true,
                    confirmButtonText: `Ok`,
                  }).then((result) => {
                    $(document).ready(function(){
                        // loadprogram('');
                        // $('#kode_program').val('');
                        // $('#nama_program').val('');
                        location.reload()
          
                    });
                  })
                }
              });
            };
