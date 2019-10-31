import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';

import {LoginComponent} from './components/login/login.component';
import {HomeComponent} from './components/home/home.component';
import {AccountsListComponent} from './components/accounts-list/accounts-list.component';
import {GoalsListComponent} from './components/goals-list/goals-list.component';
import {AccountDetailComponent} from './components/account-detail/account-detail.component';

const routes: Routes = [
    {
        path: '',
        redirectTo: 'login',
        pathMatch: 'full',
    },
    {
        path: 'login',
        component: LoginComponent,
    },
    {
        path: 'home',
        component: HomeComponent,
    },
    {
        path: 'accounts',
        component: AccountsListComponent,
    },
    {
        path: 'goals',
        component: GoalsListComponent,
    },
    {
        path: 'account/:id',
        component: AccountDetailComponent,
    },
    // {
    //     path: 'goal/:id',
    //     component: GoalDetailComponent,
    // },
];

@NgModule({
    imports: [RouterModule.forRoot(routes)],
    exports: [RouterModule]
})
export class AppRoutingModule {}