<template>
    <div>
        <div class="row">
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3 v-model="resultSet.userCount"></h3>
                        <p>Users</p>
                    </div>

                    <div class="icon">
                      <i class="fa fa-user"></i>
                    </div>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3 v-model="resultSet.projectCount"></h3>
                        <p>Projects</p>
                    </div>

                    <div class="icon">
                      <i class="ion ion-stats-bars"></i>
                    </div>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3 v-model="resultSet.taskCount"></h3>
                        <p>Tasks</p>
                    </div>

                    <div class="icon">
                      <i class="fa fa-tasks"></i>
                    </div>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3 v-model="resultSet.bugCount"></h3>
                            <p>Bugs</p>
                    </div>

                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
 
<script>
    export default {
        mounted() {
            var app = this;
            alert(this.globalUrl);
            axios.get(this.globalUrl+'api/v1/dashboard')
                .then(function (resp) {
                    console.log(resp.data);
                    alert('success');
                    app.resultSet = resp.data;
                    
                })
                .catch(function (error) {
                    console.log(error);
                    alert("Could not load data");
                });
        },
        data: function () {
            return {
                resultSet:{
                    bugCount: 0,
                    userCount: 0,
                    projectCount: 0,
                    taskCount: 0,
                }
            }
        }
    }
</script>