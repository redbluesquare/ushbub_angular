import { Component, OnInit } from '@angular/core';
import { ApiDataService } from '../api-data.service';
import { isNumber } from 'util';

@Component({
  selector: 'app-bt-dashboard',
  templateUrl: './bt-dashboard.component.html',
  styleUrls: ['./bt-dashboard.component.css']
})
export class BtDashboardComponent implements OnInit {

  constructor(private apiDataService: ApiDataService) { }
  modalClass:string = 'modal';
  targetmodalClass:string = 'modal';
  data:any;
  account_id:number = 0;
  account_name:string;
  alias:string;
  accounts:any[];
  account_to:number=0;
  account_type:number;
  account_types:any[];
  account_number:string;
  balances:any[];
  current_date:Date;
  current_month:any;
  current_month_name:string;
  current_year:number;
  currencies:any;
  currency:number = 52;
  eAccounts:any[];
  expenses:any;
  from_date:string;
  interest_rate:string;
  monthNames:any = ["January", "February", "March", "April", "May", "June","July", "August", "September", "October", "November", "December"];
  notes:string;
  sort_code:string;
  target_balance:number;
  target_date:string;
  to_date:string;

  closeModal(){
    if(this.modalClass=='modal'){
      this.modalClass = 'modalOpen';
    }else{
      this.modalClass = 'modal';
    }
  }
  closeTargetModal(a_id=0,a_name=''){
    if(this.targetmodalClass=='modal'){
      if(a_id>0){
        this.account_id = a_id;
        this.account_name = a_name;
        this.target_balance = undefined;
      }
      this.targetmodalClass = 'modalOpen';
    }else{
      this.targetmodalClass = 'modal';
    }
  }

  getAccounts(id='',an=6){
    this.modalClass = 'modal';
    if(an==3){
      this.apiDataService.getAccounts(id,an)
        .subscribe(eaccounts => {this.eAccounts = eaccounts;});
    }
    else{
      this.apiDataService.getAccounts(id,an)
        .subscribe(accounts => {this.accounts = accounts;});
    }
    
  }

  getSummary(){
    this.apiDataService.getTransSummary(this.account_to,this.from_date,this.to_date).subscribe(expenses => this.expenses = expenses);
  }

  saveAccount(){
    this.data = {
      account_id:this.account_id,
      account_name:this.account_name,
      alias:this.alias,
      account_type:this.account_type,
      account_number:this.account_number,
      sort_code:this.sort_code,
      interest_rate:this.interest_rate,
      currency:this.currency,
      notes:this.notes
    }

    this.apiDataService.saveAccount(this.data)
      .subscribe(accounts => this.getAccounts());
  }

  saveTarget(){
    this.data = {
      account_id:this.account_id,
      target_date:this.to_date,
      target_balance:this.target_balance
    }

    this.apiDataService.saveTarget(this.data)
      .subscribe(summary => {this.getSummary();
        this.closeTargetModal();
      });
  }

  updateDate(a=undefined){
    
    if(a==0){
      this.current_date = new Date();
      this.current_year = this.current_date.getFullYear()
      this.current_month = this.current_date.getMonth()
    }
    if(a==1){
      if(this.current_month>10){
        this.current_month = 0;
        this.current_year = this.current_year+a;
      }
      else{
        this.current_month = this.current_month+a;
      }
    }
    if(a==-1){
      if(this.current_month<1){
        this.current_month = 11;
        this.current_year = this.current_year+a;
      }
      else{
        this.current_month = this.current_month+a;
      }
    }
    if(!isNumber(this.current_month)){
      this.current_month = parseInt(this.current_month);
    }
    this.current_month_name=this.monthNames[this.current_month];
    this.from_date = this.current_year+'-'+(this.current_month+1)+'-01';
    let lastDate = new Date(this.current_year,this.current_month+1,0).getDate();
    this.to_date = this.current_year+'-'+(this.current_month+1)+'-'+lastDate;
    this.getSummary();
    console.log(this.current_month+1);
  }

  ngOnInit() {
    this.apiDataService.getCurrencies()
      .subscribe(currencies => {this.currencies = currencies['currencies'];});
    this.apiDataService.getAccountTypes()
      .subscribe(account_types => this.account_types = account_types);
    this.getAccounts();
    this.getAccounts('',3);
    this.updateDate(0)
  }

}
