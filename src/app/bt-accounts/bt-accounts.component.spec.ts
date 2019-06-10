import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { BtAccountsComponent } from './bt-accounts.component';

describe('BtAccountsComponent', () => {
  let component: BtAccountsComponent;
  let fixture: ComponentFixture<BtAccountsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ BtAccountsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(BtAccountsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
