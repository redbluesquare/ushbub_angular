import { Component, OnInit, ViewChild, ElementRef } from '@angular/core';
import { FormGroup, FormBuilder, Validators, FormControl } from "@angular/forms";
import { ApiDataService } from '../api-data.service';
import { AuthService } from '../auth.service';
import { Location } from '../location';
import { StripeService, Elements, Element as StripeElement, ElementOptions, ElementsOptions } from "ngx-stripe";
import { User } from '../user';

@Component({
  selector: 'app-carwash',
  templateUrl: './carwash.component.html',
  styleUrls: ['./carwash.component.css']
})
export class CarwashComponent implements OnInit {

  card: StripeElement;
  cardinfo:any;
  card_info1:boolean;
  card_info2:boolean;
  card_value:number;
  car_id:number;
  car_make:number;
  car_model:number;
  car_reg:string;
  car_year:string;
  data:any;
  elements: Elements;
  email:string;
  first_name:string;
  fullname:string;
  last_name:string;
  location:any;
  mobile_no:string;
  modalClass:string;
  myGroup:any;
  payment_method:number;
  product_id:number;
  requestDate:string;
  state:number;
  stripeTest: FormGroup;
  cardForm:FormGroup;
  user:User;
  v_services:any;
  vehicles:any;
  vendor_id:number;
  vendor_location:number;
  vendor_locations:Location;
  service_price:number;
  // optional parameters
  elementsOptions: ElementsOptions = {
    locale: 'auto'
  };

  constructor(
    private apiService: ApiDataService, 
    private authService: AuthService,
    private fb: FormBuilder,
    private stripeService: StripeService
  ) { }

  closeModal(){
    this.modalClass = 'modal';
  }

  buy($e) {
    const name = this.stripeTest.get('name').value;
    this.stripeService
      .createToken(this.card, { name })
      .subscribe(result => {
        if (result.token) {
          this.data = {
            token:result.token,
            car:this.car_id,
            location:this.vendor_location
          };
          // Use the token to create a charge or a customer
          this.apiService.saveStripecustomer(this.data)
          .subscribe(result => {
            //TODO book cleaning service
            this.saveBooking();
          });
          this.modalClass = 'modal';
        } else if (result.error) {
          // Error creating the token
          console.log(result.error.message);
        }
      });
  }

  getCarddetails(){
    if(this.payment_method == 1){

    }
  }

  updateState(a){
    this.state = a;
  }

  selectDate(d){
    this.modalClass = 'selected';
    this.requestDate = d;
  }

  removeVehicle(id){
    alert('id:' + id + ' - remove vehicle from list');
    this.data = {
      'id':id
    }
    this.apiService.deleteVehicle(this.data)
      .subscribe(vehicles => this.updateVehicles(vehicles))
  }

  saveBooking(){
    this.data = {
      "car_id":this.car_id,
      "service_date":this.requestDate,
      "mobile_no":this.mobile_no,
      "vendor_id":1,
      "product_id":this.product_id,
      "service_price":this.service_price,
      "payment_method":this.payment_method
    }
    this.apiService.saveService(this.data)
        .subscribe(services => {
          this.v_services = services;
        });
  }

  saveLocation(id){
    //save the vendor_location as default
    this.apiService.saveCarwashLocation(id)
        .subscribe(location => this.location = location);
  }

  saveVehicle(){
    this.data = {
      car_reg:this.car_reg,
      make:this.car_make,
      model:this.car_model,
      year:this.car_year
    }
    this.apiService.saveVehicle(this.data)
      .subscribe(vehicles => this.updateVehicles(vehicles))
  }

  selectCard(a){
    if(a==1){
      this.setDefaultValues(true,false);
    }
    if(a==2){
      this.setDefaultValues(false,true);
    }
  }

  setDefaultValues(value1,value2) {
    this.card_info1 = value1;
    this.card_info2 = value2;
 }

  showStripeForm(){
    if(this.payment_method==1){
      if(this.card_info2==true){
        this.modalClass = 'modalOpen';
        this.stripeService.elements(this.elementsOptions)
          .subscribe(elements => {
            this.elements = elements;
            // Only mount the element the first time
            if (!this.card) {
              this.card = this.elements.create('card', {
                style: {
                  base: {
                    iconColor: '#666EE8',
                    color:'#31325F',
                    lineHeight: '40px',
                    fontWeight: 300,
                    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                    fontSize: '18px',
                    '::placeholder': {
                      color: '#CFD7E0'
                    }
                  }
                }
              });
              this.card.mount('#card-element');
              this.fullname = this.first_name+ " "+this.last_name;
            } 
          });
      }else{
        //TODO save payment info at customer level
        this.saveBooking();
      } 
    }else{
      //TODO save payment info as cash
    }
  }

  updateCardinfo(cardinfo){
    this.cardForm = new FormGroup({
      card_value: new FormControl()
    });
    this.cardinfo = cardinfo.cardinfo;
    if((cardinfo.cardinfo!=null) || (cardinfo.cardinfo!='')){
      this.setDefaultValues(true,false);
    }else{
      this.setDefaultValues(false,true);
    }
  }

  updateVehicles(vehicles){
    if(vehicles.length > 0){
      this.vehicles = vehicles;
      this.car_id = vehicles[0].id;
    }
  }

  updateUser(user){
    if(user.first_name!=undefined){
      this.state = 1;
      this.authService.isLoggedIn = true;
      this.first_name = user.first_name;
      if(user.last_name!=null){
        this.last_name = user.last_name;
      }else{
        this.last_name = '';
      }
      this.location = user.carwash_options;
      this.email = user.email;
      this.apiService.getVendorLocations()
        .subscribe(locations => this.vendor_locations = locations[0]);
        if(user.carwash_options.carwash_location.id==undefined){
          this.vendor_location = 0;
        }else{
          this.vendor_location = user.carwash_options.carwash_location.id;
        }
        
    }
  }

  ngOnInit() {
    this.modalClass = 'modal';
    this.state = 0;
    this.vendor_location = 0;
    this.car_id = 0;
    this.apiService.getProfiles()
    .subscribe(user => this.updateUser(user));
    this.apiService.getStripecustomer()
    .subscribe(cardinfo => this.updateCardinfo(cardinfo));
    this.stripeTest = this.fb.group({
      name: ['', [Validators.required]]
    });
    this.apiService.getVehicles()
    .subscribe(vehicles => this.updateVehicles(vehicles));
    this.apiService.getServices()
      .subscribe(v_services => this.v_services = v_services);
    this.requestDate = "2018-08-14";
    this.payment_method = 1;
    this.service_price = 10.00;
    this.product_id = 1;
  }
}
