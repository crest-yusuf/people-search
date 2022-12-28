<template>
        <div>
            <form class="form-search" @submit.prevent="search">
                <div class="row">
                <div class="form-group col-md-1"></div>
                <div class="form-group col-md-2">
                        <label for="name">First Name</label>
                        <input type="text" class="form-control" v-model="form.firstName" required>
                </div>
                <div class="form-group col-md-2">
                    <label for="lastName">Last Name</label>
                    <input type="text" class="form-control" v-model="form.lastName" required>
                </div>
                
                <div class="form-group col-md-2">
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
                
                <div class="form-group col-md-1">
                    <label></label>
                    <button type="submit" class="form-control btn btn-success" :disabled="isSearchDisabled">Search</button>
                </div>
                <div class="form-group col-md-1"></div>
            </div>
            </form>
        <div class="row">
            <div class="col-md-6"></div>
            <div class="col-md-6" v-if="total > 0"> <h3 class="record-found-title"> We found <span>{{ total }}</span> matches </h3>  </div>
        </div>
            <div class="row" v-if="total > 0">
                <div class="col-md-6">
                        <Bar :data="chartData" :options="chartOptions" />
                </div>
                <table class="table table-striped">
                    <thead>
                        <tr class="header">
                        <th scope="col" v-for="(title, key) in this.titles" :key="key">{{ title }}</th>
                        <th scope="col">Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(value, index) in this.searchData" :key="index">
                            <td>{{ value.Name }}</td>
                            <td class="td-age"> <span v-if="value.Age !=''">{{ value.Age }}</span></td>
                            <td>{{ value.phone_numbers }}</td>
                            <td>{{ value.releted_people }}</td>
                            <td>{{ value.Location }}</td>
                            <td class="td-score">{{ value | countScore(form, percentPerFillup) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="row pagination-div">
                <div class="col-md-10"></div>
                <div class="col-md-1">
                    <button v-if="currentPage > 1" class="btn btn-primary" @click="requestPage(parseInt(currentPage) - 1)">Previous</button> 
                </div>
                <div class="col-md-1">
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
                 isSearchDisabled: false,
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
                this.isSearchDisabled = true

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
                this.isSearchDisabled = false
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
table {
    margin-top: 10px;
    border-bottom-width: 0px !important;
}
.form-search {
    margin-top: 20px;
    /* background-color: #426cce; */
    padding: 10px 10px 40px;
    border-radius: 5px;
}
.form-search label {
    font-family: auto;
}

/* .td-age {
    background-color: #2b850f;
     border-radius: 15%; 
}
.td-age span {
    background: #c8a913;
    border-radius: 50%;
    padding: 5px;
}
.td-score {
    background-color: #2b850f;
    color: white !important;
    font-weight: bold;
} */

.form-search, tr.header{
    background: #426cce;
}
tr.header{
    color: white;
    border-radius: 10px;
}
.record-found-title{
    float: right;
    color: #055c05;

}

form label {
    color: white;
}

.record-found-title span{
    color: white;
    border-radius: 25%;
    background: black;
    padding: 5px;
}
.pagination-div button{
    float: left;
    margin-right: 10px
}

</style>
