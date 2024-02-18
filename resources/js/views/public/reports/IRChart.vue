<style>
.td-approve {
  color: rgb(41, 121, 255) !important;
}
.td-reject {
  color: rgb(171, 0, 60) !important;
}
.td-close {
  color: #00a732 !important;
}
.td-endorse {
  color: rgb(101, 31, 255) !important;
}
</style>

<template>
    <div>
      <div id="loader2" v-if="loader">
          <div class="loader-bar"></div>
      </div>
      <div class="col-lg-12 header-title text-center">
        {{formtitle}}
      </div>
      <!-- <button class="btn btn-primary" @click="displayChart">BUTTONCH</button> -->
      <canvas ref="pie2d"></canvas>
      <canvas ref="chart2d"></canvas>
    </div>
</template>
<script>
export default {
    components:{
        
    },
    data(){
        return{
            // ctx : '',
            forapprover: '',
            formtitle: '',
            rows: [],
            disabledinput: false,
            dtHandle: null,
            loader: true,
            approvers: [],
            reciever_emails: [],
            selected: {},
            pieChart: '',
            barChart: '',
            options: {
                type: 'line',
                data: {
                    labels: ["Jan", "Feb", "March", "April", "May", "June"],
                    // labels: [],
                    datasets: [
                            {
                                //employee
                                backgroundColor: 'rgba(87,192,225, .5)',
                                borderColor: 'rgba(87,192,225, .5)',
                                fill: false,
                                label: '# of Employees0',
                                data: [100, 10, 5, 2, 20, 30, 45],
                                borderWidth: 1
                            },
                            
                        ],

                },
                options: {
                    scales: {
                        yAxes: [{
                        ticks: {
                                    reverse: false
                        }
                    }]
                        },
                        legend:{
                            display: false
                        }
                }
            }
        }
    },
    methods:{
      setInit(data = null, labels = {pie:[], bar: []}){
        // console.log('INIT',data, labels);
        this.loader = false;
        let pieData = [];
        if(data.pie){
          for (const iterator of labels.pie) {
            pieData.push(data.pie[iterator] || 0);
          }
        }
        
      // console.log(pieData, labels.pie, data.pie['approved']);
        this.displayPieChart(labels.pie, pieData);
        this.displayBarChart(labels, data.bar);
      },
      displayPieChart(labels = '', dataSet = []){
        /**
         * Pending - black
         * approved -  #2979ff
         * endorsement - #651fff
         * closed -  #2979ff
         * rejected - #ab003c
         */
        if(this.pieChart)
        this.pieChart.destroy();

        const pieBg = this.chartBgColor(labels);
        const data2 = {
            labels: labels,
            datasets: [
            {
              label: 'My First dataset',
              backgroundColor: pieBg,
              data: dataSet,
            },
            ]
          };
        const config2 = {
            type: 'pie',
            data: data2,
            options: {}
          };
        this.pieChart = new Chart(this.$refs.pie2d, config2);
      },
      displayBarChart(labels = '', dataSet = ''){

        // console.log(labels);
        let barData = [];
        
        if(labels.pie){
          
          // main label status
          for (const iterator of labels.pie) {      
            let dataVal = [];  

            // check each branch
            for (const branchLabel of labels.bar) {

                let found = false;
                // check for data set
                for (const barData of dataSet) {
                  
                  if(branchLabel == barData.branchname){    
                    dataVal.push(barData[iterator]);  
                    found = true;
                  }
                }
                if(!found)
                dataVal.push(0);  
              
            }
            barData.push({
              label: iterator || '--',
              backgroundColor: this.chartBgColor(iterator),
              data: dataVal
            });
          }
        }
        const data = {
            labels: labels.bar,
            datasets: barData,
            // [
            // {
            //   label: 'My First dataset',
            //   backgroundColor: this.randomColors(),
            //   borderColor: 'rgb(255, 99, 132)',
            //   data: [0, 10, 5, 2, 20, 30],
            // },
            // ],
            
          };

          const config = {
            type: 'bar',
            data: data,
            options: {}
          };
        if(this.barChart)
        this.barChart.destroy();

        this.barChart = new Chart(this.$refs.chart2d, config);
      },
      chartBgColor(labels){
         /**
         * Pending - black
         * approved -  #2979ff
         * endorsement - #651fff
         * closed -  #2979ff
         * rejected - #ab003c
         */
        const colors = {
          'pending': '#333', 
          "approved": '#2979ff',
          "rejected": '#ab003c',
          "closed": '#00a732',
          "1st endorsement": '#651fff',
          "2nd endorsement": '#651fff',
          "further investigation": '#651fff',
        }

        let chartBgColor = [];
        if(typeof labels != 'string'){
          for (const iterator of labels) {
            chartBgColor.push(colors[iterator] || 0);
          }
        }
        else{
          chartBgColor = colors[labels];
        }
        

        return chartBgColor;  
      },

      randomColors(){
        var x = Math.floor(Math.random() * 256);
        var y = 100+ Math.floor(Math.random() * 256);
        var z = 50+ Math.floor(Math.random() * 256);
        return "rgba(" + x + "," + y + "," + z + ", 1)";
      }
    },
    mounted(){
      // this.ctx = document.getElementById('myChart2').getContext('2d');
          // const myChart2 = new Chart(
          //   // document.getElementById('myChart2'),
          //   this.$refs.chart2d,
          //   config
          // );
          
          // setInterval(()=>{
          //   new Chart(this.ctx, this.options);
          // }, 3000)
          
          // this.displayChart();
          bus.$on('initIRReportChart', this.setInit);
    }

}
</script>