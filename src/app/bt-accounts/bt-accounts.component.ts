import { Component, OnInit, ViewChild, ElementRef, Input } from '@angular/core';
import { ApiDataService } from '../api-data.service';
import { ActivatedRoute } from '@angular/router';
import {DateAdapter, MAT_DATE_FORMATS, MAT_DATE_LOCALE} from '@angular/material/core';

@Component({
  selector: 'app-bt-accounts',
  templateUrl: './bt-accounts.component.html',
  providers: [{provide: MAT_DATE_LOCALE, useValue: 'en-GB'},],
  styleUrls: ['./bt-accounts.component.css']
})
export class BtAccountsComponent implements OnInit {

  constructor(private apiDataService: ApiDataService,private route: ActivatedRoute,private adapter: DateAdapter<any>) { }
  account:any;
  account_name:string;
  account_number:string;
  account_type:number;
  account_types:any;
  alias:string;
  balances:any;
  balance_value:number = 0.00;
  balance_id:number;
  context:any;
  currency:number;
  currencies:any;
  data:any;
  ddc_account_id:string;
  dr_cr:string='dr';
  interest_rate:string;
  modalClass:string = 'modal';
  notes:string;
  record_date:any;
  sort_code:string;
  viewState:number = 0;

  @ViewChild('balanceCanvas') public balanceCanvas: ElementRef;

  @Input() public width = 600;
  @Input() public height = 400;

  private cx: CanvasRenderingContext2D;

  cancelEdit(){
    this.viewState = 0;
  }
  
  closeModal(){
    if(this.modalClass=='modal'){
      this.modalClass = 'modalOpen';
    }else{
      this.modalClass = 'modal';
    }
  }

  editBalance(balance){
    this.modalClass = 'modalOpen';
    if(balance == ''){
      this.balance_value = null;
      this.balance_id = null;
      this.record_date = new Date();
    }else{
      this.balance_value = balance.balance;
      this.balance_id = balance.ddc_balance_id;
      this.record_date = new Date(balance.record_date.split(" ")[0]+'T'+balance.record_date.split(" ")[1]+'Z');
    };
    console.log(balance)
  }

  getAccounts(id=''){
    this.apiDataService.getAccounts(id)
    .subscribe(account => this.account = account[0]);
  }

  getBalances(id='',acc=0){
    this.apiDataService.getBalances(id,acc)
    .subscribe(balances => {
      this.balances = balances;
      this.draw()});
  }

  getDate(date){
    return Date.parse(date.split(" ")[0]);
  }

  saveAccount(){
    this.data = {
      account_id:this.ddc_account_id,
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
      .subscribe(account => {
        this.account = account;
        this.viewState = 0;
      });
  }

  saveBalance(){
    let date = new Date(this.record_date);
    this.data = {
      record_date:new Date(date.getTime() - (date.getTimezoneOffset() * 60000 )).toISOString().split('T')[0],
      balance_value:this.balance_value,
      dr_cr:this.dr_cr,
      ddc_account_id:this.ddc_account_id,
      ddc_balance_id:this.balance_id
    };
    this.apiDataService.saveBalance(this.data)
      .subscribe(balances => {this.balances = balances;this.modalClass = 'modal';});
  }

  draw(){
    const canvasEl: HTMLCanvasElement = this.balanceCanvas.nativeElement;
    this.cx = canvasEl.getContext('2d');

    canvasEl.width = this.width;
    canvasEl.height = this.height;

    if(this.balances.length>1){

      this.cx.lineWidth = 3;
      this.cx.lineCap = 'round';
      this.cx.strokeStyle = '#000';
      let margin = 20;
      let lborder = 0;
      let rborder = 0
      let displayHeight = canvasEl.height - (2 * margin);
      let displayWidth = canvasEl.width - (2 * margin) - lborder - rborder;
      let dataXVals = new Array();
      for(let i = 0; i < this.balances.length; i++){ dataXVals.push(Number(this.balances[i].balance)); }
      dataXVals.sort(function(a,b){return a-b});
      let minVal = Number(dataXVals[0]);
      let maxVal;
      dataXVals.reverse();
      if(Number(this.balances[0].target_balance) >= dataXVals[0])
      {
        maxVal = Number(this.balances[0].target_balance);
      }else
      {
        maxVal = Number(dataXVals[0]);
      }
      let stepSize = Math.ceil((maxVal-minVal)/5);
      minVal = minVal-stepSize;
      maxVal = maxVal+stepSize;
      //get date range from query and calculate number of days
      let endDate = new Date(this.balances[0].record_date.split(" ")[0]);
      let startDate:any = new Date(this.balances[this.balances.length-1].record_date.split(" ")[0]);
      let tDays = ((endDate.getTime()-startDate.getTime()) / (24*60*60*1000));
      
      let stepDays = Math.ceil( tDays / (displayHeight/80) );
      let totalDays = (Math.ceil(tDays/stepDays) * stepDays);
      
      //define the intervals for x and y
      let xScalar = displayWidth / totalDays;
      let yScalar = (displayHeight / (maxVal - minVal));

      //create the graph background
      // print row header and draw horizontal grid lines
      this.cx.strokeStyle = "rgba(125, 125, 25, 0.1)"; // light blue lines
      this.cx.beginPath();
      // print  column header and draw vertical grid lines
      var month = (startDate.getMonth()+1).toString();
      var monthDay = startDate.getDate().toString();
      if(month.length == 1){month = "0"+month;}
      if(monthDay.length == 1){monthDay = "0"+monthDay;}
      let currDate:any = startDate.getFullYear() + "-" + month + "-" + monthDay;
      for (let i = 0; i <= Math.ceil(totalDays/stepDays); i++) 
      {
          let x = (i) * xScalar * stepDays;
          this.cx.fillText(currDate, x, canvasEl.height-margin+15);
          let nextDate = new Date(currDate).getTime() + (stepDays * (24*60*60*1000));
          currDate = new Date(nextDate);
          let month = (currDate.getMonth()+1).toString();
          let monthDay = currDate.getDate().toString();
          if(month.length == 1){month = "0"+month;}
          if(monthDay.length == 1){monthDay = "0"+monthDay;}
          currDate = currDate.getFullYear() + "-" + month + "-" + monthDay;
          this.cx.moveTo(x+lborder+margin, margin);
          this.cx.lineTo(x+lborder+margin, displayHeight+10);
      }
      // print row header and draw horizontal grid lines
      let count = 0;
      for (let scale = maxVal; scale >= minVal; scale -= stepSize) {
          let y = margin + (yScalar * count * stepSize);
          this.cx.fillText(scale.toFixed(2), ((xScalar)/4), y + 5);
          this.cx.moveTo((margin+lborder), y);
          this.cx.lineTo(displayWidth+margin+lborder, y);
          count++;
      }
      this.cx.stroke();
      //plot the target Balance
      let yVal = margin + (maxVal - Number(this.balances[0].target_balance)) * yScalar ;
      this.cx.beginPath();
      this.cx.strokeStyle = "rgb(255,5,0)";
      this.cx.moveTo((margin+lborder),yVal);
      this.cx.lineTo(displayWidth+margin+lborder,yVal);
      this.cx.stroke();
      //plot points into line graph        
      this.cx.strokeStyle = "rgb(0,255,0)";
      // set a color and make one call to plotData()
      // for each data set
      this.plotData(this.balances,this.cx,startDate,margin,lborder,maxVal,xScalar,yScalar);
    }
  }
  plotData(dataSet,ctx,startDate,margin,lborder,maxVal,xScalar,yScalar) {
    ctx.beginPath();
    for (let i = 0; i < dataSet.length; i++) {
        let day = new Date(dataSet[i].record_date.split(" ")[0]);
        let xday = (day.getTime()-startDate.getTime())/(1000*60*60*24);
        ctx.lineTo((xday * xScalar)+lborder+margin, margin + (maxVal - Number(dataSet[i].balance)) * yScalar );
    }
    ctx.stroke();
  }

  updateAccount(account){
    this.viewState = 1;
    this.account_name = account.account_name;
    this.alias = account.alias;
    this.account_number = account.account_number;
    this.sort_code = account.sort_code;
    this.account_type = account.account_type;
    this.interest_rate = account.interest_rate;
    this.currency = account.currency_id;
    this.notes = account.notes;

  }

  ngOnInit() {
    this.adapter.setLocale('en-GB');
    this.ddc_account_id = this.route.snapshot.paramMap.get('id');
    this.getAccounts(this.ddc_account_id);
    this.getBalances('',parseInt(this.ddc_account_id));
    this.record_date = new Date();
    this.apiDataService.getCurrencies()
      .subscribe(currencies => {this.currencies = currencies['currencies'];});
    this.apiDataService.getAccountTypes()
      .subscribe(account_types => this.account_types = account_types);
  }

  ngAfterViewInit(): void {
    

    
  }

}

