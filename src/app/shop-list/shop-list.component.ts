import { Component, OnInit } from '@angular/core';
import { ApiDataService } from '../api-data.service';
import { AuthService } from '../auth.service';
import { Vendor } from '../vendor';

@Component({
  selector: 'app-shop-list',
  templateUrl: './shop-list.component.html',
  styleUrls: ['./shop-list.component.css']
})
export class ShopListComponent implements OnInit {

  constructor(private authService: AuthService,private apiDataService: ApiDataService) { }

  vendors:Vendor[];
  vendor_id:number = 0;
  addshop:number;
  alias:string;
  address1:string;
  address2:string;
  city:string;
  county:string;
  country:number;
  countries:any[];
  data:any;
  description:string;
  email:string;
  full_name:string;
  introduction:string;
  post_code:string;
  shop_type:string;
  title:string;

  showShopForm(){
    this.addshop = 1;
    this.title = '';
    this.shop_type = '';
    this.description = '';
    this.full_name = '';
    this.email = '';
  }

  saveShopForm(){
    this.data = {
      vendor_id:this.vendor_id,
      title:this.title,
      introduction:this.introduction,
      description:this.description,
      address1:this.address1,
      address2:this.address2,
      city:this.city,
      county:this.county,
      country:this.country,
      post_code:this.post_code,
      shop_type:this.shop_type,
      full_name:this.full_name,
      email:this.email
    }
    this.apiDataService.saveShop(this.data)
    .subscribe(vendors => this.closeShopForm());
  }
  closeShopForm(){
    this.addshop = 0;
  }

  ngOnInit() {
    if (localStorage.getItem('isLoggedIn') == '1'){
      this.authService.isLoggedIn = true;
    }
    this.addshop = 0;
    this.apiDataService.getVendors()
    .subscribe(vendors => this.vendors = vendors);

  }

}
