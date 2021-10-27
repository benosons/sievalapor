console.log('You are running jQuery version: ' + $.fn.jquery);
$(document).ready(function(){
  $('#nav-menu li').removeClass();
  $('#nav-menu li#menu-dashboard').addClass('active');

  loadppk('');
  
  $('#paketnya').on('change', function(){
    let nama = $('option:selected', this).attr('name');
    loadtarget(this.value, nama);
  })

  

});

var options = {
  series: [
    {
      name: "Rencana",
      data: []
    },
    {
      name: "Realisasi",
      data: []
    }
  ],
  chart: {
    height: 350,
    type: 'line',
    zoom: {
      enabled: false
    }
  },
dataLabels: {
  enabled: false
},
stroke: {
  curve: 'straight'
},
title: {
  text: 'Kurva S',
  align: 'left'
},
grid: {
  row: {
    colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
    opacity: 0.5
  },
},
xaxis: {
  categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Maei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
}
};

var chart1 = new ApexCharts(document.querySelector("#chart-1"), options);
var chart2 = new ApexCharts(document.querySelector("#chart-2"), options);
chart1.render();
chart2.render();

function loadtarget(param, nama){

  $.ajax({
  type: 'post',
  dataType: 'json',
  url: 'loadtargetNip',
  data : {
          code      : param,
  },
  success: function(result){
    let data = result.data;

    for (var i = 0; i < data.length; i++) {
      if(data[i].type == 'keuangan'){
        var element1 = [];
        var element2 = [];
        var last1 = [];
        var last2 = [];
        for (let index = 1; index <= 12; index++) {
          if(data[i]['n'+index] != ""){
            element1['n'+index] = data[i]['n'+index].replaceAll('.', '');
            last1.push(index);
          }else{
            if(last1.length != 0){
              element1['n'+index] = data[i]['n'+last1[last1.length - 1]].replaceAll('.', '')
            }
          }

          
          if(!_.isEmpty(data[i]['progres']['n'+index])){
            element2['n'+index] = data[i]['progres']['n'+index]['tot'];
            last2.push(index);
          }else{
            if(last2.length != 0){
              element2['n'+index] = data[i]['progres']['n'+last2[last2.length - 1]]['tot']
            }
          }
        }
        
        var rencana = [
          (element1['n1']) ? element1['n1'] : 0,
          (element1['n2']) ? element1['n2'] : 0,
          (element1['n3']) ? element1['n3'] : 0,
          (element1['n4']) ? element1['n4'] : 0,
          (element1['n5']) ? element1['n5'] : 0,
          (element1['n6']) ? element1['n6'] : 0,
          (element1['n7']) ? element1['n7'] : 0,
          (element1['n8']) ? element1['n8'] : 0,
          (element1['n9']) ? element1['n9'] : 0,
          (element1['n10']) ? element1['n10'] : 0,
          (element1['n11']) ? element1['n11'] : 0,
          (element1['n12']) ? element1['n12'] : 0
        ]

        var Realisasi = [
          (element2['n1']) ? element2['n1'] : 0,
          (element2['n2']) ? element2['n2'] : 0,
          (element2['n3']) ? element2['n3'] : 0,
          (element2['n4']) ? element2['n4'] : 0,
          (element2['n5']) ? element2['n5'] : 0,
          (element2['n6']) ? element2['n6'] : 0,
          (element2['n7']) ? element2['n7'] : 0,
          (element2['n8']) ? element2['n8'] : 0,
          (element2['n9']) ? element2['n9'] : 0,
          (element2['n10']) ? element2['n10'] : 0,
          (element2['n11']) ? element2['n11'] : 0,
          (element2['n12']) ? element2['n12'] : 0
        ]
        
        var options1 = {
          series: [
            {
              name: "Rencana",
              data: rencana
            },
            {
              name: "Realisasi",
              data: Realisasi
            }
          ],


        title: {
          text: 'Kurva S Keuangan - '+nama,
          align: 'left'
        },

        };
        
        // var chart1 = new ApexCharts(document.querySelector("#chart-1"), options1);
        chart1.updateOptions(options1);

      }else if(data[i].type == 'fisik'){
        var element3 = [];
        var element4 = [];
        var last3 = [];
        var last4 = [];
        for (let index = 1; index <= 12; index++) {
          if(data[i]['n'+index] != ""){
            element3['n'+index] = data[i]['n'+index];
            last3.push(index);
          }else{
            if(last3.length != 0){
              element3['n'+index] = data[i]['n'+last3[last3.length - 1]]
            }
          }

          
          if(!_.isEmpty(data[i]['progres']['n'+index])){
            element4['n'+index] = data[i]['progres']['n'+index]['tot'];
            last4.push(index);
          }else{
            if(last4.length != 0){
              element4['n'+index] = data[i]['progres']['n'+last4[last4.length - 1]]['tot']
            }
          }
        }
        
        var rencana_f = [
          (element3['n1']) ? element3['n1'] : 0,
          (element3['n2']) ? element3['n2'] : 0,
          (element3['n3']) ? element3['n3'] : 0,
          (element3['n4']) ? element3['n4'] : 0,
          (element3['n5']) ? element3['n5'] : 0,
          (element3['n6']) ? element3['n6'] : 0,
          (element3['n7']) ? element3['n7'] : 0,
          (element3['n8']) ? element3['n8'] : 0,
          (element3['n9']) ? element3['n9'] : 0,
          (element3['n10']) ? element3['n10'] : 0,
          (element3['n11']) ? element3['n11'] : 0,
          (element3['n12']) ? element3['n12'] : 0
        ]

        var realisasi_f = [
          (element4['n1']) ? element4['n1'] : 0,
          (element4['n2']) ? element4['n2'] : 0,
          (element4['n3']) ? element4['n3'] : 0,
          (element4['n4']) ? element4['n4'] : 0,
          (element4['n5']) ? element4['n5'] : 0,
          (element4['n6']) ? element4['n6'] : 0,
          (element4['n7']) ? element4['n7'] : 0,
          (element4['n8']) ? element4['n8'] : 0,
          (element4['n9']) ? element4['n9'] : 0,
          (element4['n10']) ? element4['n10'] : 0,
          (element4['n11']) ? element4['n11'] : 0,
          (element4['n12']) ? element4['n12'] : 0
        ]

        var options2 = {
          series: [
            {
              name: "Rencana",
              data: rencana_f
            },
            {
              name: "Realisasi",
              data: realisasi_f
            }
          ],
        
        title: {
          text: 'Kurva S Fisik - '+nama,
          align: 'left'
        },
        
        };

        // var chart2 = new ApexCharts(document.querySelector("#chart-2"), options2);
        chart2.updateOptions(options2);
      }
    }
    }
  })
}

function loadppk(param){

  $.ajax({
      type: 'post',
      dataType: 'json',
      url: 'loadnip',
      data : {
              code      : param,
      },
      success: function(result){
          let data = result.data;
          let code = result.code;
          let el1   = '<option value=""></option>';
          if(code == 1){
            for (let index = 0; index < data.length; index++) {
              
              const id = data[index].id;
              const nama_paket = data[index].nama_paket;
              el1 += '<option value="'+id+'" name="'+nama_paket+'">'+nama_paket+'</option>';
              
            }

            $('#paketnya').html(el1);
            $('#paketnya').trigger("chosen:updated");

          }
        }
      })
    }
