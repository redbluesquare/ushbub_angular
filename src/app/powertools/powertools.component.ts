import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { Location } from '@angular/common';
import { ApiDataService } from '../api-data.service';
import { Category } from '../category';
import { Connector } from '../connector';
import { Product } from '../product';
import { trigger, state, style, animate, transition } from '@angular/animations';

@Component({
  selector: 'app-powertools',
  templateUrl: './powertools.component.html',
  styleUrls: ['./powertools.component.css'],
  animations: [ trigger('slideInOut', [
    state('in', style({
      overflow: 'hidden',
      height: '*'
    })),
    state('out', style({
      opacity: '0',
      overflow: 'hidden',
      height: '0px'
    })),
    transition('in => out', animate('400ms ease-out')),
    transition('out => in', animate('400ms ease-in'))
  ])
  ]
})
export class PowertoolsComponent implements OnInit {

  itemState1:string;
  itemState2:string;
  id:string;

  constructor(
    private apiDataService: ApiDataService,
    private route: ActivatedRoute,
    private location: Location
  ) { }

  toggleState(a): void {
    if(a === 1){
      this.itemState1 = this.itemState1 === 'in' ? 'out' : 'in';
    }
    else{
      this.itemState2 = this.itemState2 === 'in' ? 'out' : 'in';
    }
    
  }

  ngOnInit() {
    this.id = this.route.snapshot.paramMap.get('alias');
    this.itemState1 = 'out';
    this.itemState2 = 'out';
  }

  

}
