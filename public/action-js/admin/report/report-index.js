"use strict";
console.log('You are running jQuery version: ' + $.fn.jquery);
$(document).ready(function(){
  $('#nav-menu li').removeClass();
  $('#nav-menu li#menu-report').addClass('active');

  $('#all-report').DataTable({
    "ordering": false
  });

  loadall('n1');

});

function loadall(param){

  $.ajax({
      type: 'post',
      dataType: 'json',
      url: 'loadall',
      data : {
              code      : param,
      },
      success: function(result){
          let data = result.data;
          var prog = '';
          for (var i = 0; i < data.length; i++) {
            
           prog += `<tr>
                    <td></td>
                    <td>`+data[i].kode_program+`</td>
                    <td>`+data[i].nama_program+`</td>
                    <td>`+rubah(data[i].pagu_program)+`</td>
                    <td>`+rubah(data[i].target_keu_program)+`</td>
                    <td>`+data[i].target_persen_keu_program+`</td>
                    <td>`+rubah(data[i].real_keu_program)+`</td>
                    <td>`+data[i].real_persen_keu_program+`</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>`+data[i].target_fis_program+`</td>
                    <td>`+data[i].real_fis_program+`</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                  </tr>`;
                  var kegiatan = data[i].kegiatan;
                  if (typeof(kegiatan) === 'object') {
                    for (var i = 0; i < kegiatan.length; i++) {
                      
                      prog += `<tr>
                              <td></td>
                              <td>`+kegiatan[i].kode_kegiatan+`</td>
                              <td>`+kegiatan[i].nama_kegiatan+`</td>
                              <td>`+rubah(kegiatan[i].pagu_kegiatan)+`</td>
                              <td>`+rubah(kegiatan[i].target_keu_kegiatan)+`</td>
                              <td>`+kegiatan[i].target_persen_keu_kegiatan+`</td>
                              <td>`+rubah(kegiatan[i].real_keu_kegiatan)+`</td>
                              <td>`+kegiatan[i].real_persen_keu_kegiatan+`</td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td>`+kegiatan[i].target_fis_kegiatan+`</td>
                              <td>`+kegiatan[i].real_fis_kegiatan+`</td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                            </tr>`;

                      var subkegiatan = kegiatan[i].subkegiatan;
                      for (var i = 0; i < subkegiatan.length; i++) {
                        
                        prog += `<tr>
                                <td>1</td>
                                <td>`+subkegiatan[i].kode_subkegiatan+`</td>
                                <td>`+subkegiatan[i].nama_subkegiatan+`</td>
                                <td>`+rubah(subkegiatan[i].pagu_subkegiatan)+`</td>
                                <td>`+rubah(subkegiatan[i].target_keu_subkegiatan)+`</td>
                                <td>`+subkegiatan[i].target_persen_keu_subkegiatan+`</td>
                                <td>`+rubah(subkegiatan[i].real_keu_subkegiatan)+`</td>
                                <td>`+subkegiatan[i].real_persen_keu_subkegiatan+`</td>
                                <td>`+subkegiatan[i].dev_keu_subkegiatan+`</td>
                                <td>---</td>
                                <td>---</td>
                                <td>`+subkegiatan[i].target_fis_subkegiatan+`</td>
                                <td>`+subkegiatan[i].real_fis_subkegiatan+`</td>
                                <td>`+subkegiatan[i].dev_fis_subkegiatan+`</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                              </tr>`;

                        var paket = subkegiatan[i].paket;
                        for (var i = 0; i < paket.length; i++) {
                          var target = paket[i].target;

                          var target_keu = target[0].keuangan;
                          var target_persen_keu = parseInt(target_keu.replaceAll('.', '')) / parseInt(paket[i].pagu_paket.replaceAll('.', ''));
                          var target_fis = target[0].fisik;
                          
                          var realisasi = paket[i].realisasi;

                          var ppk = realisasi[0].ppk[0].user_fullname;
                         
                          var real_keu = realisasi[0].keuangan.new_total;
                          if(real_keu){
                            var real_persen_keu = parseInt(real_keu.replaceAll('.', '')) / parseInt(paket[i].pagu_paket.replaceAll('.', ''));
                          }else{
                            var real_persen_keu = 0;
                          }

                          var real_fisik = realisasi[0].fisik.total;

                          var dev_keu = parseInt(real_persen_keu)-parseInt(target_persen_keu);
                          var dev_fis = parseInt(real_fisik)-parseInt(target_fis);
                          var koordinat = realisasi[0].koordinat;
                          var latar_belakang = realisasi[0].latar_belakang;
                          var uraian = realisasi[0].uraian;
                          var permasalahan = realisasi[0].permasalahan;
                          
                          prog += `<tr>
                                  <td></td>
                                  <td></td>
                                  <td>`+paket[i].nama_paket+`</td>
                                  <td>`+paket[i].pagu_paket+`</td>
                                  <td>`+target_keu+`</td>
                                  <td>`+target_persen_keu+`</td>
                                  <td>`+rubah(real_keu)+`</td>
                                  <td>`+real_persen_keu+`</td>
                                  <td>`+dev_keu+`</td>
                                  <td></td>
                                  <td></td>
                                  <td>`+target_fis+`</td>
                                  <td>`+real_fisik+`</td>
                                  <td>`+dev_fis+`</td>
                                  <td>`+koordinat+`</td>
                                  <td>`+latar_belakang+`</td>
                                  <td>`+uraian+`</td>
                                  <td>`+permasalahan+`</td>
                                  <td>`+ppk+`</td>
                                </tr>`;

                        }

                        
                        
                      }


                    }


                    }



          }
          $('#data_all').html(prog);
        //   var dt = $('#all-rekap').DataTable({
        //     destroy: true,
        //     paging: true,
        //     lengthChange: false,
        //     searching: true,
        //     ordering: true,
        //     info: true,
        //     autoWidth: false,
        //     responsive: false,
        //     pageLength: 10,
        //     aaData: result.data,
        //     aoColumns: [
        //         { 'mDataProp': 'id', 'width':'10%'},
        //         { 'mDataProp': 'nip'},
        //         { 'mDataProp': 'user_fullname'},
        //         { 'mDataProp': 'nama_paket'},
        //         { 'mDataProp': 'pagu'},
        //     ],
        //     order: [[0, 'ASC']],
        //     fixedColumns: true,
        //     aoColumnDefs:[
        //       { width: 50, targets: 0 },
        //       {
        //           mRender: function ( data, type, row ) {
        //
        //             var el = `
        //                       <button class="btn btn-xs btn-primary" onclick="window.location.href = 'rekap?param=view&ids=`+row.id+`'">
        //                         <i class="ace-icon fa fa-search bigger-120"></i>
        //                       </button>
        //                       <button class="btn btn-xs btn-success" onclick="action(\'delete\','+row.id+',\'\')">
				// 												<i class="ace-icon fa fa-edit bigger-120"></i>
				// 											</button>
        //                       <button class="btn btn-xs btn-danger" onclick="action(\'delete\','+row.id+',\'\')">
        //   											<i class="ace-icon fa fa-trash-o bigger-120"></i>
        //   										</button>`;
        //
        //               return el;
        //           },
        //           aTargets: [4]
        //       },
        //     ],
        //     fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull){
        //         var index = iDisplayIndexFull + 1;
        //         $('td:eq(0)', nRow).html('#'+index);
        //         return  index;
        //     },
        //     fnInitComplete: function () {
        //
        //         var that = this;
        //         var td ;
        //         var tr ;
        //         this.$('td').click( function () {
        //             td = this;
        //         });
        //         this.$('tr').click( function () {
        //             tr = this;
        //         });
        //     }
        // });

        }
      })
    }

    function rubah(angka){
      var reverse = angka.toString().split('').reverse().join(''),
      ribuan = reverse.match(/\d{1,3}/g);
      ribuan = ribuan.join('.').split('').reverse().join('');
      return ribuan;
    }
