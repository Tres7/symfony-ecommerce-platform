// assets/controllers/credit_card_form_collection_controller.js
import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['card'];

    connect() {
        this.cardIndex = this.cardTargets.length;
    }

    add() {
        const template = `
            <div class="card-form">
                <label for="credit_card_${this.cardIndex}_number">Numéro de carte</label>
                <input type="text" name="credit_cards[${this.cardIndex}][number]" class="form-control" />
                
                <label for="credit_card_${this.cardIndex}_expirationDate">Date d'expiration</label>
                <input type="date" name="credit_cards[${this.cardIndex}][expirationDate]" class="form-control" />
                
                <label for="credit_card_${this.cardIndex}_cvv">CVV</label>
                <input type="text" name="credit_cards[${this.cardIndex}][cvv]" class="form-control" />
            </div>
        `;

        this.element.insertAdjacentHTML('beforeend', template);
        this.cardIndex++;
    }
}
