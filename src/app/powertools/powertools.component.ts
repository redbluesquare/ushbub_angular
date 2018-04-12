import { Component, OnInit } from '@angular/core';
import { ApiDataService } from '../api-data.service';
import { Category } from '../category';
import { Connector } from '../connector';

@Component({
  selector: 'app-powertools',
  templateUrl: './powertools.component.html',
  styleUrls: ['./powertools.component.css']
})
export class PowertoolsComponent implements OnInit {

  categories:Category[];
  connectors:Connector[];
  progress:number;
  product_type:string;
  product_alias:string;
  connector:string;

  constructor(private apiDataService: ApiDataService) { }

  getCategories(): void {
    this.apiDataService.getCategories('power-tools')
    .subscribe(categories => this.categories = categories);
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
  }

  goBack(){
    if(this.progress==1){
      this.progress = 0;
      localStorage.removeItem('acc_product_type');
      localStorage.removeItem('acc_product_alias');
      localStorage.setItem('acc_progress','0');
      this.product_type = null;
    }
    if(this.progress==2){

    }
  }

  ngOnInit() {
    this.getCategories();
    if(localStorage.getItem('acc_progress')==null || localStorage.getItem('acc_progress')=='0'){
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
      this.progress = parseInt(localStorage.getItem('acc_progress'));
      this.product_type = localStorage.getItem('acc_product_type');
    }
  }
  checkLink(s){
    if(s[0]=='i')
    {
      s = 'assets/'+s;
    }
    return s;
  }

}
