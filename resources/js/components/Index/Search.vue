<template>
        <div class="justify-content-center">
            <div class="row  col-md-12">
                    <div class="card-header">Search</div>
            </div>
            <form @submit.prevent="search">
                <div class="form-group">
                    <div class="col-md-3">
                        <label for="name">First Name</label>
                        <input type="text" class="form-control" v-model="form.firstName" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-3">
                    <label for="lastName">Last Name</label>
                    <input type="text" class="form-control" v-model="form.lastName" required>
                </div>
                </div>
                

                <div class="form-group col-md-3">
                    <label for="City">City</label>
                    <input type="text" class="form-control" v-model="form.city">
                </div>
                <div class="form-group col-md-3">
                    <label for="state">State</label>
                    <select id="state" class="form-control" name="state" v-model="form.state">
                        <option value="">All States</option>
                        <template v-for="(state,key) in states">
                            <option :value="state.code">{{ state.label }}</option>
                        </template>
                    </select>
                </div>
                <br>
                <div class="form-group col-md-3">
                    <button type="submit" class="form-control btn btn-info">Search</button>
                </div>
            </form>
        <div class="row">
            <div class="col-md-6" v-if="total > 0"> We found {{ total }} matches</div>
        </div>
            <div class="row" v-if="total > 0">
                <div class="col-md-6">
                        <Bar :data="chartData" :options="chartOptions" />
                </div>
                <table class="table">
                    <thead>
                        <tr>
                        <th scope="col" v-for="(title, key) in this.titles" :key="key">{{ title }}</th>
                        <th scope="col">Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(value, index) in this.searchData" :key="index">
                            <td>{{ value.Name }}</td>
                            <td>{{ value.Age }}</td>
                            <td>{{ value.phone_numbers }}</td>
                            <td>{{ value.releted_people }}</td>
                            <td>{{ value.Location }}</td>
                            <td>{{ value | countScore(form, percentPerFillup) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-md-6" >
                    <button v-if="currentPage > 1" class="btn btn-primary" @click="requestPage(parseInt(currentPage) - 1)">Previous</button>
                    <button v-if="currentPage < totalPages" class="btn btn-primary" @click="requestPage(parseInt(currentPage) + 1)">Next</button>
                </div>
            </div>
        </div>                    
</template>

<script>

import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale
} from 'chart.js'
import { Bar } from 'vue-chartjs'
ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

    export default {
        components: { Bar },
        filters: {
            countScore: (currentRecord, searchRecord, percentPerFillup) => {
                console.log('currentRecord',percentPerFillup, searchRecord.city, searchRecord.state)

                let score = percentPerFillup + percentPerFillup             
            if(searchRecord.city != '' && currentRecord.Location.toLowerCase().includes(searchRecord.city.toLowerCase())) {
                score += percentPerFillup
            }
            
            if(searchRecord.state != '' &&  currentRecord.Location.toLowerCase().includes(searchRecord.state.toLowerCase())) {
                score +=percentPerFillup
            }
                // console.log('form', this.form)
                return Math.round(score)
            }
        },
        computed: {
        chartData() {             
            let UpdateData = {
                    labels: [ 'Under 30', '30 to 50', 'Above 50' ],
                    datasets: [ {
                        label: 'Found Data',
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            ],
                            borderColor: [
                            'rgb(255, 99, 132)',
                        ],   
                         data: this.chartArray } ]
                }
            return UpdateData
         },
        },
        data: () => {

            return {
                chartArray: [],
                oldchartData: {
                    labels: [ 'Under 30', '30 to 50', 'Above 50' ],
                    datasets: [ {
                        label: 'Found',
                        backgroundColor: [
                            'rgba(255, 255, 132, 0.2)',
                            ],
                            borderColor: [
                            'rgb(255, 99, 132)',
                        ],   
                         data: [40, 20, 12] } ]
                },
                chartOptions: {
                    responsive: true
                },
                form:{ 
                    firstName:'',
                    lastName:'',
                    city:'',
                    state:'',
                 },
                 total: 0,
                 perPage: 20,
                 totalPages: 1,
                 currentPage: 1, 
                 searchData: [],
                 titles: [],
                 states:[                    
                        { code: "AL" , label : 'Alabama'}, { code: "AK" , label : 'Alaska'}, { code: "AZ" , label : 'Arizona'}, { code: "AR" , label : 'Arkansas'},{ code: "CA" , label : 'California'},
                        { code: "CO" , label : 'Colorado'},{ code: "CT" , label : 'Connecticut'},{ code: "DE" , label : 'Delaware'},{ code: "FL" , label : 'Florida'},{ code: "GA" , label : 'Georgia'},
                        { code: "HI" , label : 'Hawaii'},{ code: "ID" , label : 'Idaho'},{ code: "IL" , label : 'Illinois'},{ code: "IN" , label : 'Indiana'},{ code: "IA" , label : 'Iowa'},{ code: "KS" , label : 'Kansas'},
                        { code: "KY" , label : 'Kentucky'},{ code: "LA" , label : 'Louisiana'}, { code: "ME" , label : 'Maine'}, { code: "MD" , label : 'Maryland'}, { code: "MA" , label : 'Massachusetts'},
                        { code: "MI" , label : 'Michigan'}, { code: "MN" , label : 'Minnesota'}, { code: "MS" , label : 'Mississippi'}, { code: "MO" , label : 'Missouri'}, { code: "MT" , label : 'Montana'}, 
                        { code: "NE" , label : 'Nebraska'},{ code: "NV" , label : 'Nevada'},{ code: "NH" , label : 'New Hampshire'},{ code: "NJ" , label : 'New Jersey'},{ code: "NM" , label : 'New Mexico'},
                        { code: "NY" , label : 'New York'},{ code: "NC" , label : 'North Carolina'},{ code: "ND" , label : 'North Dakota'},{ code: "OH" , label : 'Ohio'},{ code: "OK" , label : 'Oklahoma'},
                        { code: "OR" , label : 'Oregon'},{ code: "PA" , label : 'Pennsylvania'},{ code: "RI" , label : 'Rhode Island'},{ code: "SC" , label : 'South Carolina'},{ code: "SD" , label : 'South Dakota'},
                        { code: "TN" , label : 'Tennessee'},{ code: "TX" , label : 'Texas'},{ code: "UT" , label : 'Utah'},{ code: "VT" , label : 'Vermont'},{ code: "VA" , label : 'Virginia'},{ code: "WA" , label : 'Washington'},
                        { code: "WV" , label : 'West Virginia'},{ code: "WI" , label : 'Wisconsin'}, { code: "WY" , label : 'Wyoming'}, { code: "DC" , label : 'District of Columbia'}    
                 ],
                 percentPerFillup: 0,

            }
        },
        methods:{
            countFillup() {
                let count = 0;
                for (const key in this.form) {

                    if(this.form[key] != '') {
                        count+=1
                    }
                    console.log(`${key}: ${this.form[key]}`);
                }
                this.percentPerFillup = (100/count)
            },
            requestPage(pageNumber) {
                this.currentPage = pageNumber
                let url = `${this.$baseUrl}/request-page?page=${pageNumber}`;
                this.axios.get(url).then((response) => {
                const { data } = response.data
                this.searchData = data.list
                this.titles = data.titles
                this.prepareChart()
                })
            },
            search() {
                this.countFillup()
                // return false
                let searchUrl = `${this.$baseUrl}/search`;
                this.axios.post(searchUrl,this.form).then((response) => {
                const { data } = response.data
                this.searchData = data.list
                this.titles = data.titles
                this.currentPage = 1
                // this.currentPage = data.currentPage
                this.total = data.total
                this.getTotalPages()
                this.prepareChart()
                })
            },
            getTotalPages(){        
                this.totalPages = Math.ceil(this.total / this.perPage);
            },
            prepareChart () {
                let Under30 = 0;
                let thirty1toFifty = 0;
                let FiftyAbove = 0;
                for (let i = 0; i < this.searchData.length; i++) {
                    let age = this.searchData[i].Age
                    if (age <= 30) {
                        Under30+=1
                    }

                    if (age >= 31  && age <= 50) {
                        thirty1toFifty+=1
                    }

                    if (age >= 51) {
                        FiftyAbove+=1
                    }
                }
                this.chartArray = [Under30, thirty1toFifty, FiftyAbove]
                // console.log('Under30', Under30, thirty1toFifty)

            }
        },
        mounted() {
            console.log('Component mounted.', this.chartData)
        }
    }
</script>
<style scoped>
.row {
    margin-top: 20px;
}
table.result-list {
    margin-top: 10px;
}
</style>
