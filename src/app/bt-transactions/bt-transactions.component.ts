import { Component, OnInit, Pipe, PipeTransform } from '@angular/core';
import { ApiDataService } from '../api-data.service';
import { User } from '../user';
import {DateAdapter, MAT_DATE_FORMATS, MAT_DATE_LOCALE} from '@angular/material/core';

@Component({
  selector: 'app-bt-transactions',
  templateUrl: './bt-transactions.component.html',
  providers: [{provide: MAT_DATE_LOCALE, useValue: 'en-GB'},],
  styleUrls: ['./bt-transactions.component.css']
})
export class BtTransactionsComponent implements OnInit {

  constructor(private apiDataService:ApiDataService,private adapter: DateAdapter<any>) { }
  accounts:any;
  account_from:number=0;
  account_from_name:string;
  account_to:number=0;
  account_to_name:string;
  account_nature:number = 0;
  category:number = 0;
  data:any = {};
  matDatepicker:any;
  modalClass:string;
  record_date:any;
  state:number = 0;
  transaction_description:string;
  transaction_value:number = 0.00;
  transactions:any;
  trans_filter:any;
  total_value:number;
  user:User;

  getAccounts(id='',cat=0){
    this.modalClass = 'modal';
    this.apiDataService.getAccounts(id,cat)
    .subscribe(accounts => this.accounts = accounts);
  }

  getTransactions(){
    this.apiDataService.getTransactions()
      .subscribe(transactions => {
        this.transactions = transactions;
        this.trans_filter = transactions;
      });
    
  }

  getTransTotals(column) : number {
    let sum = 0;
    if(this.trans_filter!=undefined){
      for(let i = 0; i < this.trans_filter.length; i++) {
        sum += this.trans_filter[i][column];
      }
    }
    return sum;
  }

  loadUser(user){
    this.user = user;
  }

  saveTrans(){
    this.data = {
      record_date:this.record_date.toISOString().split('T')[0],
      transaction_description:this.transaction_description,
      transaction_value:this.transaction_value,
      transaction_type:this.category,
      account_from:this.account_from,
      account_to:this.account_to
    };
    
    this.apiDataService.saveTransaction(this.data)
      .subscribe(transactions => {this.transactions = transactions;
        this.state = 1;
      });
  }

  updateAN(b){
    if(b>-1){
      this.category = b;
    }
  }

  updateFilter(a,b){
    //a is the type (0 = record_date | 1 = account_from | 2 = account_from_name | 3 = account_to | 4 = account_to_name)
    if(a==0){
      this.trans_filter = this.transactions.
        filter(function(p) { return p.record_date == b });
    }
    if(a==1){
      this.account_from = b;
    }
    if(a==2){
      this.trans_filter = this.transactions.
        filter(function(p) { return p.account_from_name == b });
    }
    if(a==3){
      this.account_to = b;
    }
    if(a==4){
      this.trans_filter = this.transactions.
        filter(function(p) { return p.account_to_name == b });
    }
    if(a==5){
      this.trans_filter = this.transactions;
    }
  }

  updateTrans(a,b){
    this.state = a;
    this.updateAN(b);
    if(a==3){
      this.getAccounts('',this.category);
    }
    if(a==5){
      if(this.category==3){
        this.getAccounts('',6);
      }else{
        this.getAccounts();
      }
    }
  }

  updateAcc(c,d){
    this.apiDataService.getAccounts(d.toString(),this.account_nature)
    .subscribe(acc => {
      if(c==0){
        this.account_to_name = acc[0].account_name;
      }
      if(c==1){
        this.account_from_name = acc[0].account_name;
      }
    });
      
  }

  ngOnInit() {
    this.adapter.setLocale('gb');
    this.apiDataService.getProfiles()
    .subscribe(user => this.loadUser(user));
    this.record_date = new Date();
    this.getTransactions();
  }

}
