import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { BtTransactionsComponent } from './bt-transactions.component';

describe('BtTransactionsComponent', () => {
  let component: BtTransactionsComponent;
  let fixture: ComponentFixture<BtTransactionsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ BtTransactionsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(BtTransactionsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
