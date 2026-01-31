 <script>
    const cartList = document.getElementById("cartList");
    const wishlistList = document.getElementById("wishlistList");
    const cartCount = document.getElementById("cartCount");
    const wishCount = document.getElementById("wishCount");
    let cartTotal = 0, wishTotal = 0;

    // Handle quantity buttons
    document.querySelectorAll(".product").forEach((card) => {
      const qtyEl = card.querySelector(".quantity");
      let qty = 1;

      card.querySelectorAll(".qty-btn")[0].addEventListener("click", () => {
        if (qty > 1) qty--;
        qtyEl.textContent = qty;
      });
      card.querySelectorAll(".qty-btn")[1].addEventListener("click", () => {
        qty++;
        qtyEl.textContent = qty;
      });

      // Add to cart
      card.querySelector(".add-cart").addEventListener("click", (e) => {
        const title = card.querySelector("h3").innerText;
        const price = parseInt(card.querySelector(".price").innerText) * qty;
        const li = document.createElement("li");
        li.textContent = `${title} × ${qty} — Rs ${price}`;
        li.className = "p-2 bg-white rounded shadow-sm";
        cartList.appendChild(li);
        cartTotal++;
        cartCount.textContent = `🛒 ${cartTotal}`;
        e.target.textContent = "✅ Added";
        e.target.classList.add("bg-green-600");
        setTimeout(() => {
          e.target.textContent = "🛒 Add to Cart";
          e.target.classList.remove("bg-green-600");
        }, 1500);
      });

      // Add to wishlist
      card.querySelector(".add-wishlist").addEventListener("click", (e) => {
        const title = card.querySelector("h3").innerText;
        const price = card.querySelector(".price").innerText;
        const li = document.createElement("li");
        li.textContent = `${title} — Rs ${price}`;
        li.className = "p-2 bg-white rounded shadow-sm";
        wishlistList.appendChild(li);
        wishTotal++;
        wishCount.textContent = `💖 ${wishTotal}`;
        e.target.textContent = "💖 Added";
        e.target.classList.add("bg-pink-700");
        setTimeout(() => {
          e.target.textContent = "❤️ Wishlist";
          e.target.classList.remove("bg-pink-700");
        }, 1500);
      });
    });
  </script>