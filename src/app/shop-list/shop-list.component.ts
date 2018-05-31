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
  full_name:string;
  email:string;
  shop_type:string;

  showShopForm(){
    this.addshop = 1;
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
