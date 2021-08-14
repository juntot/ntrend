<template>
    <div>
        <div class="col-lg-12">
            <h4>DASHBOARD</h4>

        </div>
        <div class="col-lg-12 canvas-chart">
            <div id="chart" class="dflex justify-space-between">
                <section class="text-center">
                        <p class="legend-p"><i class="fas fa-users color-sky"></i></p>
                        <div class="legend bgc-sky"></div>
                        <p class="legend-p legend-value" id="emp">{{empcount}}</p>
                </section>
                <section class="text-center">
                        <p class="legend-p"><i class="fas fa-project-diagram color-red"></i></p>
                        <div class="legend bgc-red"></div>
                        <p class="legend-p legend-value" id="dept">{{deptcount}}</p>
                </section>
                <section class="text-center">
                        <p class="legend-p"><i class="fas fa-comment-dots color-navy"></i></p>
                        <div class="legend bgc-navy"></div>
                        <p class="legend-p legend-value" id="post">{{postcount}}</p>
                </section>
                <section class="text-center">
                        <p class="legend-p"><i class="fas fa-sitemap color-pumpkin"></i></p>
                        <div class="legend bgc-pumpkin"></div>
                        <p class="legend-p legend-value" id="branch">{{branchcount}}</p>
                </section>
            </div>
		</div>
		<div class="col-lg-12">
			<section>
				<canvas id="chartJSContainer" height="100px"></canvas>
			</section>
		</div>
    </div>
</template>

<script>
// var options = {
//   type: 'line',
//   data: {
//     labels: ["Jan", "Feb", "March", "April", "May", "June"],
//     datasets: [
// 	    {
// 				backgroundColor: 'rgba(87,192,225, .5)',
// 				borderColor: 'rgba(87,192,225, .5)',
// 				fill: false,
// 	      label: '# of Votes',
// 	      data: [12, 19, 3, 5, 2, 3],
//       	borderWidth: 1
//     	},
// 			{
// 				backgroundColor: 'rgba(215,58,77, .5)',
// 				borderColor: 'rgba(215,58,77, .5)',
// 				fill: false,
// 				label: '# of Points',
// 				data: [7, 11, 5, 8, 3, 7],
// 				borderWidth: 1
// 			},
// 			{
// 				backgroundColor: 'rgba(0,119,181, .5)',
// 				borderColor: 'rgba(0,119,181, .5)',
// 				fill: false,
// 				label: '# of Points',
// 				data: [23, 14, 10, 15, 13, 27],
// 				borderWidth: 1
// 			},
// 			{
// 				backgroundColor: 'rgba(255,129,38, .5)',
// 				borderColor: 'rgba(255,129,38, .5)',
// 				fill: false,
// 				label: '# of Points',
// 				data: [47, 21, 25, 18, 13, 27],
// 				borderWidth: 1
// 			},
// 		],

//   },
//   options: {
//   	scales: {
//     	yAxes: [{
//         ticks: {
// 					reverse: false
//         }
//       }]
// 		},
// 		legend:{
// 			display: false
// 		}
//   }
// }

export default {

    data(){
        return{
            ctx : '',
            postcount: 0,
            empcount: 0,
            deptcount: 0,
            branchcount: 0,
            options: {
                type: 'line',
                data: {
                    // labels: ["Jan", "Feb", "March", "April", "May", "June"],
                    labels: [],
                    datasets: [
                            {
                                //employee
                                backgroundColor: 'rgba(87,192,225, .5)',
                                borderColor: 'rgba(87,192,225, .5)',
                                fill: false,
                                label: '# of Employees0',
                                data: [],
                                borderWidth: 1
                            },
                            {
                                //department
                                backgroundColor: 'rgba(215,58,77, .5)',
                                borderColor: 'rgba(215,58,77, .5)',
                                fill: false,
                                label: '# of Department0',
                                data: [],
                                borderWidth: 1
                            },
                            {
                                // posts
                                backgroundColor: 'rgba(0,119,181, .5)',
                                borderColor: 'rgba(0,119,1801, .5)',
                                fill: false,
                                label: '# of Posts 0',
                                data: [],
                                borderWidth: 1
                            },
                            {
                                // branch
                                backgroundColor: 'rgba(255,129,38, .5)',
                                borderColor: 'rgba(255,129,38, .5)',
                                fill: false,
                                label: '# of Branch0',
                                data: [],
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
        animateText(){
            let self = this;
            $('p.legend-value').each(function (index , el) {
            let text = self.postcount;
            if(el.getAttribute("id") == 'emp')
                text = self.empcount;
            if(el.getAttribute("id") == 'dept')
                text = self.deptcount;
            if(el.getAttribute("id") == 'branch')
                text = self.branchcount;

            var $this = $(this);
                jQuery({ counter: 0 }).animate({ counter: text }, {
                    duration: 1500,
                    easing: 'swing',
                    step: function () {
                        $this.text(Math.ceil(this.counter));
                    }
                });
            });

        },
        setPoints(data = null , len = 0)
        {
            let result = [];
            for(let i = 0 ; i < len; i++)
            {
                result[i] = 0;
            }
            data.forEach((val, index)=>{
                result[(parseInt(val.months)-1)] = val.counter;

            });
            return result;
        }
    },
    mounted(){
        this.ctx = document.getElementById('chartJSContainer').getContext('2d');

    },
    created(){
        // months
       moment.monthsShort().forEach((val, index)=>{
           if(index <= new Date().getMonth())
           {
               this.options.data.labels.push(val);
            }
       });

        axios.get('api/graph')
        .then((response)=>{
            let len  = this.options.data.labels.length;
            this.options.data.datasets[0].data= this.setPoints(response.data.emp, len);
            this.options.data.datasets[1].data= this.setPoints(response.data.dept, len);
            this.options.data.datasets[2].data= this.setPoints(response.data.post, len);
            this.options.data.datasets[3].data= this.setPoints(response.data.branch, len);

            // GET TOTAL COUNTS
            this.postcount = response.data.postCount.count;
            this.empcount = response.data.empCount.count;
            this.deptcount = response.data.deptCount.count;
            this.branchcount = response.data.branchCount.count;
            this.animateText();
            new Chart(this.ctx, this.options);
        })
        .catch((err)=>{ console.log(err);});
    }
}
</script>



//  $('p.legend-value').each(function () {
            // var $this = $(this);
            //     jQuery({ counter: 0 }).animate({ counter: $this.text() }, {
            //         duration: 1500,
            //         easing: 'swing',
            //         step: function () {
            //             $this.text(Math.ceil(this.counter));
            //         }
            //     });
            // });