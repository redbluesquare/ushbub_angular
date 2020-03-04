import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '../../../node_modules/@angular/router';
import { ApiDataService } from '../api-data.service';
import { AuthService } from '../auth.service';
import { Product } from '../product';
import { Vendor } from '../vendor';

@Component({
  selector: 'app-shop',
  templateUrl: './shop.component.html',
  styleUrls: ['./shop.component.css']
})
export class ShopComponent implements OnInit {
  products: Product[];
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
  productEdit:boolean=false;
  shop_type:string;
  title:string;
  vendor_id:number;
  vendors:Vendor[];
  shopEdit:boolean = false;
  ddc_vendor_product_id:number;
  ddc_product_price_id:number
  product_id:number;
  product_type:number;
  distrib_cat_id:number;
  vendor_product_sku:string;
  product_gtin:string;
  product_asin:string;
  product_mpn:string;
  vendor_product_name:string;
  vendor_product_alias:string;
  product_description_small:string;
  product_description:string;
  category_id:number;
  product_weight:number;
  product_weight_uom:string;
  product_length:number;
  product_width:number;
  product_height:number;
  product_lwh_uom:string;
  low_stock_notification:number;
  product_available_date:any;
  product_availability:string;
  product_special:number;
  product_base_uom:string;
  product_packaging:number;
  product_params:string;
  product_price:number;
  product_price_estimate:number;
  product_currency:string;
  hits:number;
  intnotes:string;
  metarobot:string;
  metaauthor:string;
  layout:string;
  published:number;
  pordering:number;
  created_on:any;
  created_by:number;
  modified_on:any;
  modified_by:number;
  locked_on:any;
  locked_by:number;

  constructor(
    private authService: AuthService,
    private apiDataService: ApiDataService,
    private route: ActivatedRoute) { }

  edit(check){
    if(check){
      if(this.vendors[0].admin==true){
        this.shopEdit = true;
      }
    }
    else{
      this.shopEdit = false;
    }
  }

  editProduct(check,product){
    if(check){
      this.productEdit = true;
      this.ddc_vendor_product_id=product.ddc_vendor_product_id;
      this.product_id=product.product_id;
      this.product_type=product.product_type;
      this.distrib_cat_id=product.distrib_cat_id;
      this.vendor_id=this.vendor_id;
      this.vendor_product_sku=product.vendor_product_sku;
      this.product_gtin=product.product_gtin;
      this.product_asin=product.product_asin;
      this.product_mpn=product.product_mpn;
      this.vendor_product_name=product.vendor_product_name;
      this.vendor_product_alias=product.vendor_product_alias;
      this.product_description_small=product.product_description_small;
      this.product_description=product.product_description;
      this.category_id=product.category_id;
      this.product_weight=product.product_weight;
      this.product_weight_uom=product.product_weight_uom;
      this.product_length=product.product_length;
      this.product_width=product.product_width;
      this.product_height=product.product_height;
      this.product_lwh_uom=product.product_lwh_uom;
      this.low_stock_notification=product.low_stock_notification;
      this.product_available_date=product.product_available_date;
      this.product_availability=product.product_availability;
      this.product_special=product.product_special;
      this.product_base_uom=product.product_base_uom;
      this.product_packaging=product.product_packaging;
      this.product_params=product.product_params;
      this.product_price=product.product_price;
      this.product_currency=product.product_currency;
      this.hits=product.hits;
      this.intnotes=product.intnotes;
      this.metarobot=product.metarobot;
      this.metaauthor=product.metaauthor;
      this.layout=product.layout;
      this.published=product.published;
      this.pordering=product.pordering;
      this.created_on=product.created_on;
      this.created_by=product.created_by;
      this.modified_on=product.modified_on;
      this.modified_by=product.modified_by;
    }
    else{
      this.productEdit = false;
    }
  }
  
  getProducts(alias,type=1): void {
    this.apiDataService.getProducts(alias,type)
    .subscribe(products => this.products = products);
  }
  
  getVendors(vendors){
    this.vendors = vendors;
    this.vendor_id=vendors[0].ddc_vendor_id;
    this.title = vendors[0].title;
    this.introduction = vendors[0].introduction;
    this.description = vendors[0].description;
    this.address1 = vendors[0].address1;
    this.address2 = vendors[0].address2;
    this.city = vendors[0].city;
    this.post_code = vendors[0].post_code;
    this.country = vendors[0].country;
    this.shop_type = vendors[0].shop_type;
    this.email = vendors[0].vendor_details.contact_email;
  }

  saveProduct(){
    this.data = {
      ddc_vendor_product_id:this.ddc_vendor_product_id,
      product_id:this.product_id,
      product_type:this.product_type,
      distrib_cat_id:this.distrib_cat_id,
      vendor_id:this.vendor_id,
      vendor_product_sku:this.vendor_product_sku,
      product_gtin:this.product_gtin,
      product_asin:this.product_asin,
      product_mpn:this.product_mpn,
      vendor_product_name:this.vendor_product_name,
      vendor_product_alias:this.vendor_product_alias,
      product_description_small:this.product_description_small,
      product_description:this.product_description,
      category_id:this.category_id,
      product_weight:this.product_weight,
      product_weight_uom:this.product_weight_uom,
      product_length:this.product_length,
      product_width:this.product_width,
      product_height:this.product_height,
      product_lwh_uom:this.product_lwh_uom,
      low_stock_notification:this.low_stock_notification,
      product_available_date:this.product_available_date,
      product_availability:this.product_availability,
      product_special:this.product_special,
      product_base_uom:this.product_base_uom,
      product_packaging:this.product_packaging,
      product_params:this.product_params,
      product_price:this.product_price,
      product_currency:this.product_currency,
      hits:this.hits,
      intnotes:this.intnotes,
      metarobot:this.metarobot,
      metaauthor:this.metaauthor,
      layout:this.layout,
      published:this.published,
      pordering:this.pordering,
    }
    this.apiDataService.saveProduct(this.data)
    .subscribe(products => this.editProduct(false,''));
  }

  saveShop(){
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
      .subscribe(vendors => {
        this.edit(false);
        this.apiDataService.getVendors(this.alias)
        .subscribe(vendors => this.getVendors(vendors));
      });
  }

  ngOnInit() {
    if (localStorage.getItem('isLoggedIn') == '1'){
      this.authService.isLoggedIn = true;
    }
    this.alias = this.route.snapshot.paramMap.get('alias');
    this.getProducts(this.alias);
    this.apiDataService.getVendors(this.alias)
    .subscribe(vendors => this.getVendors(vendors));
    this.apiDataService.getCountries()
    .subscribe(countries => this.countries = countries);
  }

}
