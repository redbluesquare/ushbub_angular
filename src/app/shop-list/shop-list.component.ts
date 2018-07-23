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
  addshop:number;
  title:string;
  description:string;
  first_name:string;
  last_name:string;
  email:string;
  shop_type:string;
  data:{};

  showShopForm(){
    this.addshop = 1;
    this.title = '';
    this.shop_type = '';
    this.description = '';
    this.first_name = '';
    this.last_name = '';
    this.email = '';
  }

  saveShopForm(){
    this.data = {
      title:this.title,
      shop_type:this.shop_type,
      description:this.description,
      first_name:this.first_name,
      last_name:this.last_name,
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
      return true;
    }
    this.addshop = 0;
    this.apiDataService.getVendors()
    .subscribe(vendors => this.vendors = vendors);

  }

}
