import {Component, Input} from '@angular/core';

@Component({
    selector: 'error',
    templateUrl: './error.component.html',
    styleUrls: ['./error.component.css']
})
export class ErrorComponent {
    @Input() private errors: String[];

    constructor() {}
}