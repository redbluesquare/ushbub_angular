import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PtAccessoriesComponent } from './pt-accessories.component';

describe('PtAccessoriesComponent', () => {
  let component: PtAccessoriesComponent;
  let fixture: ComponentFixture<PtAccessoriesComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PtAccessoriesComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PtAccessoriesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
