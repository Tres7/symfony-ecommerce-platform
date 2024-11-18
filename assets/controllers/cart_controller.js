import { Controller } from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['cartTotal', 'cartContainer']
    // ...

    async addToCart(event) {
        event.preventDefault();

        // id of the product to add to cart
        const productId = event.currentTarget.dataset.productId;

        // url to add the product to the cart
        const url = `/cart/add/${productId}`;

        try {
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error(`Erreur HTTP : ${response.status}`);
            }
            const data = await response.json();
            // update the cart total
            if (data.totalItems !== undefined) {
                this.updateCartCount(data.totalItems);
            }

        } catch (error) {
            console.error("Erreur lors de l'ajout au panier :", error);
        }
    }


    async updateCartCount(totalItems) {
        console.log('Mise à jour du compteur :', totalItems);

        const cartCountElement = document.querySelector('#cart-count');
        if (cartCountElement) {
            cartCountElement.textContent = totalItems;
        } else {
            console.error("cartCount target not found in DOM!");
        }
    }

    async increaseQuantity(event) {
        event.preventDefault();
        const productId = event.currentTarget.dataset.productId;
        console.log(`Increasing quantity for product ID: ${productId}`);

        await this.updateQuantity(productId, '/cart/increase/');
    }

    async decreaseQuantity(event) {
        event.preventDefault();
        const productId = event.currentTarget.dataset.productId;
        console.log(`Decreasing quantity for product ID: ${productId}`);

        await this.updateQuantity(productId, '/cart/decrease/');
    }

    async updateQuantity(productId, urlPrefix) {
        const url = `${urlPrefix}${productId}`;

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }

            const data = await response.json();

            if (data.success) {
                console.log(`Updated quantity for product ID: ${productId}`);
                this.updateRowQuantity(productId, data.quantity);
                this.updateRowSubtotal(productId, data.subtotal);
                this.updateCartTotal(data.cartTotal);
                this.updateCartCount(data.totalItems);
            }
        } catch (error) {
            console.error("Error updating quantity:", error);
        }
    }

    updateRowQuantity(productId, quantity) {
        const row = document.querySelector(`[data-product-id="${productId}"]`);
        if (row) {
            const quantityElement = row.querySelector('.quantity');
            if (quantityElement) {
                quantityElement.textContent = quantity;
            }
        }
    }

    updateRowSubtotal(productId, subtotal) {
        const row = document.querySelector(`[data-product-id="${productId}"]`);
        if (row) {
            const subtotalElement = row.querySelector('.subtotal');
            if (subtotalElement) {
                subtotalElement.textContent = `${subtotal} €`;
            }
        }
    }

    updateCartTotal(cartTotal) {
        const cartTotalElement = document.querySelector('#cart-total');
        if (cartTotalElement) {
            cartTotalElement.textContent = `${cartTotal} €`;
        }
    }

    async removeItem(event) {
        event.preventDefault();

        const productId = event.currentTarget.dataset.productId;
        console.log("Removing product ID:", productId);

        const url = `/cart/remove/${productId}`;

        try {
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (!response.ok)
            {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();
            if (data.success)
            {
                document.querySelector('.cart-content').innerHTML = data.cartHtml;

                const cartCountElement = document.querySelector('#cart-count');
                if (cartCountElement)
                {
                    cartCountElement.textContent = data.totalItems;
                }
            }
        } catch (error) {
            console.error("Erreur lors de la suppression de l'article :", error);
        }
    }




}
