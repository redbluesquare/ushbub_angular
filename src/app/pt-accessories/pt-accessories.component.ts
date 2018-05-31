import { Component, OnInit } from '@angular/core';
import { ApiDataService } from '../api-data.service';
import { Category } from '../category';
import { Connector } from '../connector';
import { Product } from '../product';
import { trigger, state, style, animate, transition } from '@angular/animations';

@Component({
  selector: 'app-pt-accessories',
  templateUrl: './pt-accessories.component.html',
  styleUrls: ['./pt-accessories.component.css'],
  animations: [ trigger('itemState', [
      state('inactive', style({
        height: '0px',display: 'none'
      })),
      state('active',   style({
        height: '340px',display: 'block'
      })),
      transition('inactive => active', animate('500ms ease-in')),
      transition('active => inactive', animate('500ms ease-out'))
    ])
  ]
})
export class PtAccessoriesComponent implements OnInit {

  categories:Category[];
  products:Product[];
  connectors:Connector[];
  progress:number;
  product_type:string;
  product_alias:string;
  connector:string;
  material_cat:string;
  mat_sub_cat:string;
  img_ptselect:string;
  img_connselect:string;
  img_matselect:string;
  img_matsubselect:string;
  img_prodselect:string;
  state:string;

  constructor(private apiDataService: ApiDataService) { }

  toggleState() {
    if(this.progress == 0){
      this.state = 'active';
    }
    else{
      this.state = 'inactive';
    }
  }

  getCategories(a): void {
    this.apiDataService.getCategories(a)
    .subscribe(categories => this.categories = categories);
  }

  getProducts(a): void {
    this.apiDataService.getProducts(a)
    .subscribe(products => this.products = products);
  }

  getConnectors(a): void {
    this.apiDataService.getConnectors(a)
    .subscribe(connectors => this.connectors = connectors);
  }
  onSelectCat(a,b){
    this.getConnectors(a);
    if(this.progress==0){
      this.progress = this.progress+1;
      localStorage.setItem('acc_progress',String(this.progress));
      this.product_type = b;
      this.product_alias = a;
      localStorage.setItem('acc_product_type', b);
      localStorage.setItem('acc_product_alias', a);
    }
    else{
      this.progress = 1;
      localStorage.setItem('acc_progress',String(1));
    }
    this.toggleState();
  }
  onSelectConnector(conn){
    this.getCategories('48');
    if(this.progress==1){
      this.connector = conn;
      localStorage.setItem('acc_connector',conn);
      this.progress = this.progress+1;
      localStorage.setItem('acc_progress',String(this.progress));
    }
  }

  onSelectMattype(id, cat){
    if(this.progress==3){
      localStorage.setItem('acc_mat_sub_cat_id',id);
      localStorage.setItem('acc_mat_sub_cat',cat);
      this.mat_sub_cat = cat;
      this.progress = this.progress+1;
      localStorage.setItem('acc_progress',String(this.progress));
      this.getProducts(id);
    }
    if(this.progress==2){
      this.getCategories(id);
      localStorage.setItem('acc_material_cat_id',id);
      localStorage.setItem('acc_material_cat',cat);
      this.material_cat = cat;
      this.progress = this.progress+1;
      localStorage.setItem('acc_progress',String(this.progress));
    }
  }

  goBack(){
    if(this.progress==1){
      this.progress = 0;
      localStorage.removeItem('acc_product_type');
      localStorage.removeItem('acc_product_alias');
      localStorage.setItem('acc_progress','0');
      this.product_type = null;
      this.getCategories('power-tools');
    }
    if(this.progress==2){
      this.progress = 1;
      localStorage.setItem('acc_progress',String(this.progress));
      localStorage.removeItem('acc_connector');
      this.getConnectors(localStorage.getItem('acc_product_alias'));
      this.connector = "";
    }
    if(this.progress==3){
      this.progress = 2;
      localStorage.setItem('acc_progress',String(this.progress));
      localStorage.removeItem('acc_material_cat_id');
      localStorage.removeItem('acc_material_cat');
      this.getCategories('48');
      this.material_cat = "";
    }
    if(this.progress==4){
      this.progress = 3;
      localStorage.setItem('acc_progress',String(this.progress));
      localStorage.removeItem('acc_mat_sub_cat_id');
      localStorage.removeItem('acc_mat_sub_cat');
      this.getCategories(localStorage.getItem('acc_material_cat_id'));
      this.mat_sub_cat = "";
    }
    this.toggleState();
  }

  checkLink(s){
    if(s[0]=='i')
    {
      s = 'assets/'+s;
    }
    return s;
  }

  ngOnInit() {
    this.state = 'inactive';
    if(localStorage.getItem('acc_progress')==null || localStorage.getItem('acc_progress')=='0'){
      this.getCategories('power-tools');
      localStorage.setItem('acc_progress','0');
      this.progress = 0;
      localStorage.removeItem('acc_product_type');
      localStorage.removeItem('acc_product_alias');
    }else{
      if(localStorage.getItem('acc_progress')=='1'){
        this.getConnectors(localStorage.getItem('acc_product_type'));
        this.progress = parseInt(localStorage.getItem('acc_progress'));
        this.product_type = localStorage.getItem('acc_product_type');
      }
      if(localStorage.getItem('acc_progress')=='2'){
        this.getCategories('48');
        this.connector = localStorage.getItem('acc_connector');
        this.progress = parseInt(localStorage.getItem('acc_progress'));
        this.product_type = localStorage.getItem('acc_product_type');
      }
      if(localStorage.getItem('acc_progress')=='3'){
        this.getCategories(localStorage.getItem('acc_material_cat_id'));
        this.material_cat = localStorage.getItem('acc_material_cat');
        this.connector = localStorage.getItem('acc_connector');
        this.progress = parseInt(localStorage.getItem('acc_progress'));
        this.product_type = localStorage.getItem('acc_product_type');
      }
      if(localStorage.getItem('acc_progress')=='4'){
        this.getProducts(localStorage.getItem('acc_mat_sub_cat_id'));
        this.material_cat = localStorage.getItem('acc_material_cat');
        this.connector = localStorage.getItem('acc_connector');
        this.progress = parseInt(localStorage.getItem('acc_progress'));
        this.product_type = localStorage.getItem('acc_product_type');
        this.mat_sub_cat = localStorage.getItem('acc_mat_sub_cat');
      }
    }
    this.img_ptselect = 'assets/images/prod_type_select.png';
    this.img_connselect = 'assets/images/connector_select.png';
    this.img_matselect = 'assets/images/mat_select.png';
    this.img_matsubselect = 'assets/images/mat_sub_select.png';
    this.img_prodselect = 'assets/images/prod_select.png';
    this.toggleState();
  }

}
