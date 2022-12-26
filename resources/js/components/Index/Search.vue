<template>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Search</div>

                    <div class="card-body row">
                        <form>
                            <div class="form-group col-md-3">
                                <label for="name">First Name</label>
                                <input type="text" class="form-control" v-model="form.firstName">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="lastName">Last Name</label>
                                <input type="text" class="form-control" v-model="form.lastName">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="state">State</label>
                                <input type="text" class="form-control" v-model="form.state">
                            </div>
                            <br>
                            <div class="form-group col-md-3">
                                <button type="submit" @click="search" class="form-control btn btn-info">Search</button>
                            </div>
                        </form>

                        <table class="table">
                        <thead>
                            <tr>
                            <th scope="col" v-for="(title, key) in this.titles" :key="key">{{ title }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(value, index) in this.searchData" :key="index">
                                <td>{{ value.Name }}</td>
                                <td>{{ value.Age }}</td>
                                <td>{{ value.phone_numbers }}</td>
                                <td>{{ value.releted_people }}</td>
                                <td>{{ value.Location }}</td>
                            </tr>
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
</template>

<script>
    export default {
        data: () => {
            return {
                form:{ 
                    firstName:'',
                    lastName:'',
                    state:'',
                 },
                 searchData: [],
                 titles: []

            }
        },
        methods:{
            search() {

                let searchUrl = `${this.$baseUrl}/search`;
                let form = {
                    firstName: 'Yusuf',
                    lastName: 'Patel',
                    state: ''
                }
                console.log(this.form);
                this.axios.post(searchUrl,this.form).then((response) => {
                const { data } = response.data
                this.searchData = data.list
                this.titles = data.titles
                })
            }
        },
        mounted() {
            // this.search()
            console.log('Component mounted.')
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
