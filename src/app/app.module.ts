import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { FormsModule,ReactiveFormsModule } from '@angular/forms'; // <-- NgModel lives here
import {HttpClientModule} from '@angular/common/http';
import { environment } from '../environments/environment';
// Import your library
import { NgxStripeModule } from 'ngx-stripe';
import {MatAutocompleteModule} from '@angular/material/autocomplete';
import {MatBadgeModule} from '@angular/material/badge';
import {MatBottomSheetModule} from '@angular/material/bottom-sheet';
import {MatButtonModule} from '@angular/material/button';
import {MatButtonToggleModule} from '@angular/material/button-toggle';
import {MatCardModule} from '@angular/material/card';
import {MatCheckboxModule} from '@angular/material/checkbox';
import {MatChipsModule} from '@angular/material/chips';
import {MatStepperModule} from '@angular/material/stepper';
import {MatDatepickerModule} from '@angular/material/datepicker';
import {MatDialogModule} from '@angular/material/dialog';
import {MatDividerModule} from '@angular/material/divider';
import {MatExpansionModule} from '@angular/material/expansion';
import {MatGridListModule} from '@angular/material/grid-list';
import {MatIconModule} from '@angular/material/icon';
import {MatInputModule} from '@angular/material/input';
import {MatListModule} from '@angular/material/list';
import {MatMenuModule} from '@angular/material/menu';
import {MatNativeDateModule, MatRippleModule} from '@angular/material/core';
import {MatPaginatorModule} from '@angular/material/paginator';
import {MatProgressBarModule} from '@angular/material/progress-bar';
import {MatProgressSpinnerModule} from '@angular/material/progress-spinner';
import {MatRadioModule} from '@angular/material/radio';
import {MatSelectModule} from '@angular/material/select';
import {MatSidenavModule} from '@angular/material/sidenav';
import {MatSliderModule} from '@angular/material/slider';
import {MatSlideToggleModule} from '@angular/material/slide-toggle';
import {MatSnackBarModule} from '@angular/material/snack-bar';
import {MatSortModule} from '@angular/material/sort';
import {MatTableModule} from '@angular/material/table';
import {MatTabsModule} from '@angular/material/tabs';
import {MatToolbarModule} from '@angular/material/toolbar';
import {MatTooltipModule} from '@angular/material/tooltip';
import {MatTreeModule} from '@angular/material/tree';
import { AppComponent } from './app.component';
import { LoginComponent } from './login/login.component';
import { ToolbarComponent } from './toolbar/toolbar.component';
import { AppRoutingModule } from './app-routing.module';
import { ApiDataService } from './api-data.service';
import { CollectionsComponent } from './collections/collections.component';
import { AuthGuardService } from './auth-guard.service';
import { AuthService } from './auth.service';
import { HomeComponent } from './home/home.component';
import { ResourcesComponent } from './resources/resources.component';
import { ShopComponent } from './shop/shop.component';
import { ProductListComponent } from './product-list/product-list.component';
import { PowertoolsComponent } from './powertools/powertools.component';
import { PtAccessoriesComponent } from './pt-accessories/pt-accessories.component';
import { FishingComponent } from './fishing/fishing.component';
import { ShopListComponent } from './shop-list/shop-list.component';
import { EventListComponent } from './event-list/event-list.component';
import { ActivityListComponent } from './activity-list/activity-list.component';
import { ProfileComponent } from './profile/profile.component';
import { CarwashComponent } from './carwash/carwash.component';
import { BlogListComponent } from './blog-list/blog-list.component';
import { BlogComponent } from './blog/blog.component';
import { ServiceListComponent } from './service-list/service-list.component';
import { BtDashboardComponent } from './bt-dashboard/bt-dashboard.component';
import { BtTransactionsComponent } from './bt-transactions/bt-transactions.component';
import { BtAccountComponent } from './bt-account/bt-account.component';
import { BtAccountsComponent } from './bt-accounts/bt-accounts.component';


@NgModule({
  declarations: [
    AppComponent,
    LoginComponent,
    ToolbarComponent,
    CollectionsComponent,
    HomeComponent,
    ResourcesComponent,
    ShopComponent,
    ProductListComponent,
    PowertoolsComponent,
    PtAccessoriesComponent,
    FishingComponent,
    ShopListComponent,
    EventListComponent,
    ActivityListComponent,
    ProfileComponent,
    CarwashComponent,
    BlogListComponent,
    BlogComponent,
    ServiceListComponent,
    BtDashboardComponent,
    BtTransactionsComponent,
    BtAccountComponent,
    BtAccountsComponent
  ],
  imports: [
    AppRoutingModule,
    BrowserModule,
    BrowserAnimationsModule,
    FormsModule,
    MatNativeDateModule,
    ReactiveFormsModule,
    HttpClientModule,
    NgxStripeModule.forRoot(environment.stripePublicKey)
  ],
  exports: [
    MatAutocompleteModule,
    MatBadgeModule,
    MatBottomSheetModule,
    MatButtonModule,
    MatButtonToggleModule,
    MatCardModule,
    MatCheckboxModule,
    MatChipsModule,
    MatStepperModule,
    MatDatepickerModule,
    MatDialogModule,
    MatDividerModule,
    MatExpansionModule,
    MatGridListModule,
    MatIconModule,
    MatInputModule,
    MatListModule,
    MatMenuModule,
    MatNativeDateModule,
    MatPaginatorModule,
    MatProgressBarModule,
    MatProgressSpinnerModule,
    MatRadioModule,
    MatRippleModule,
    MatSelectModule,
    MatSidenavModule,
    MatSliderModule,
    MatSlideToggleModule,
    MatSnackBarModule,
    MatSortModule,
    MatTableModule,
    MatTabsModule,
    MatToolbarModule,
    MatTooltipModule,
    MatTreeModule
  ],
  providers: [ApiDataService, AuthGuardService, AuthService],
  bootstrap: [AppComponent]
})
export class AppModule { }
