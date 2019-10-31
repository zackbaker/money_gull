import {Component, OnInit} from '@angular/core';

import {AccountService} from '../../shared/services/account.service';
import {UserService} from '../../shared/services/user.service';
import {User} from '../../shared/models/user.model';
import {Account} from '../../shared/models/account.model';

@Component({
    selector: 'accounts-list',
    templateUrl: './accounts-list.component.html',
    styleUrls: ['./accounts-list.component.css']
})
export class AccountsListComponent implements OnInit {
    private add_account_visible: Boolean = false;
    private account_visible: Boolean = true;
    private user: User;
    private message: String;
    private accounts: Account[];

    constructor(
        private userService: UserService,
        private accountService: AccountService,
    ) {}

    ngOnInit(): void {
        this.user = this.userService.getUser();
        this.setAccounts(this.user.id);
    }

    setAccounts(userId): void {
        this.accountService.getAllAccounts(userId).then(
            (accounts) => {
                if (accounts.hasOwnProperty('error')) {
                    this.message = accounts.error.message;
                } else {
                    this.accounts = accounts.data;
                }
            }
        );
    }

    addAccount(account: Account) {
        this.accounts.push(account);
    }

    showAddAccount(): void {
        this.account_visible = false;
        this.add_account_visible = true;
    }

    hideAddAccount(): void {
        this.add_account_visible = false;
        this.account_visible = true;
    }
}