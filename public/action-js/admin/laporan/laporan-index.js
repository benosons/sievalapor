"use strict";
console.log('You are running jQuery version: ' + $.fn.jquery);
$(document).ready(function(){
  $('#nav-menu li').removeClass();
  $('#nav-menu li#menu-laporan').addClass('active');

  $('#all-realisasi').DataTable();

  loadtarget('');

});

function loadtarget(param){

  $.ajax({
      type: 'post',
      dataType: 'json',
      url: 'loadrealisasi',
      data : {
              code      : param,
      },
      success: function(result){
          let data = result.data;
          let code = result.code;
          if(code == 1){
            var dt = $('#all-realisasi').DataTable({
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
                  { 'mDataProp': 'kode_kegiatan' , 'width':'10px'},
                  { 'mDataProp': 'kode_subkegiatan' , 'width':'10px'},
                  { 'mDataProp': 'nama_paket'},
                  { 'mDataProp': 'pagu' , 'className': "text-right"},
                  { 'mDataProp': 'pagu'},
              ],
              order: [[0, 'ASC']],
              fixedColumns: true,
              aoColumnDefs:[
                { width: 50, targets: 0 },
                {
                    mRender: function ( data, type, row ) {

                      var el = `Rp. `+data;

                        return el;
                    },
                    aTargets: [5]
                },
                {
                    mRender: function ( data, type, row ) {

                      var el = `
                                <button class="btn btn-xs btn-primary" onclick="window.location.href = 'laporan?param=view&ids=`+row.id+`'">
                                  <i class="ace-icon fa fa-search bigger-120"></i>
                                </button>
                                `;

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
          }

        }
      })
    }
