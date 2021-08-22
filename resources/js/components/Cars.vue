<template>
    <div>
        <h2>Cars</h2>
        <form class="mb-3" @submit.prevent="addCar">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Make" v-model="car.make">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Model" v-model="car.model">
            </div>
            <div class="form-group">
                <input type="number" class="form-control" placeholder="Price" v-model="car.price">
            </div>
            <div class="form-group mb-2">
                <textarea type="text" class="form-control" placeholder="Description" v-model="car.description"></textarea>
            </div>
            <div class="d-grid gap-2 form-group">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>    
        </form>
        <div class="card card-body mb-2" v-for="car in cars" v-bind:key="car.id">
            <h3>{{ car.make }} {{ car.model }}</h3>
            <h4>{{ car.price }} TND</h4>
            <p>{{ car.description }}</p>
            <button @click="editCar(car)" class="btn btn-warning mb-2">Edit</button>      
            <button @click="deleteCar(car.id)" class="btn btn-danger">Delete</button>      
        </div>
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <li v-bind:class="[{disabled: !pagination.c_prev_page_url}]" class="page-item">
                    <a class="page-link" href="#" @click="fetchCars(pagination.c_prev_page_url)">Previous</a>
                </li>
                <li class="page-item disabled">
                    <a class="page-link text-dark" href="#"> 
                        Page {{ pagination.c_current_page }} of {{ pagination.c_last_page }}
                    </a>
                </li>
                <li v-bind:class="[{disabled: !pagination.c_next_page_url}]" class="page-item">
                    <a class="page-link" href="#" @click="fetchCars(pagination.c_next_page_url)">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>
export default {
    data() {
        return {
            cars: [],
            car: {
                id: '',
                make: '',
                model: '',
                description: '',
                price: ''
            },
            car_id: '',
            pagination: {},
            edit: false
        }
    },

    created() {
        this.fetchCars();
    },

    methods: {
        fetchCars(page_url) {
            let vm = this;
            page_url = page_url || 'api/cars'
            fetch(page_url)
                .then(res => res.json())
                .then(res => {
                    this.cars = res.data;
                    vm.makePagination(res.current_page, res.last_page, res.next_page_url, res.prev_page_url);
                })
                .catch(err => console.log(err));
        },
        makePagination(currentPage , lastPage, nextPageUrl, prevPageUrl) {
            let pagination = {
                c_current_page: currentPage,
                c_last_page: lastPage,
                c_next_page_url: nextPageUrl,
                c_prev_page_url: prevPageUrl,
            }

            this.pagination = pagination;
        },
        deleteCar(id) {
            if (confirm('are you sure?')) {
                fetch(`api/cars/${id}`, {
                    method: 'delete'
                })
                .then(res => res.json())
                .then(data => {
                    alert('Car Removed');
                    this.fetchCars();
                })
                .catch(err => console.log(err));
            }
        },
        addCar() {
            if (this.edit === false) {
                //add
                fetch('api/cars', {
                    method: 'post',
                    body: JSON.stringify(this.car),
                    headers: {
                        'content-type': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    this.car.make = '';
                    this.car.model = '';
                    this.car.price = '';
                    this.car.description = '';
                    alert('Car added');
                    this.fetchCars();
                })
                .catch(err => console.log(err));
            } else {
                //edit
                fetch(`api/cars/${this.car.id}`, {
                    method: 'put',
                    body: JSON.stringify(this.car),
                    headers: {
                        'content-type': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    this.car.make = '';
                    this.car.model = '';
                    this.car.price = '';
                    this.car.description = '';
                    alert('Car Updated');
                    this.fetchCars();
                })
                .catch(err => console.log(err));
            }
        },
        editCar(car) {
            this.edit = true;
            this.car.id = car.id;
            this.car.car_id = car.id;
            this.car.make = car.make;
            this.car.model = car.model;
            this.car.price = car.price;
            this.car.description = car.description;
        }
    }
}
</script>