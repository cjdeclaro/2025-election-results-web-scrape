# 🗳️ 2025 Election Results Web Scraper

Scrapes the **2025 Philippine Election** data from the [COMELEC Transparency Server](https://2025electionresults.comelec.gov.ph/dashboard) and processes it into usable, compressed JSON files.

- 📦 **Used by**: [cjdeclaro/mapping-the-ph](https://github.com/cjdeclaro/mapping-the-ph)  
- 🔗 **Based on**: [ianalis/scraper2025](https://github.com/ianalis/scraper2025)  
- ⚙️ **Made with**: 100% **vanilla PHP**

---

## 🚀 What It Does

This tool scrapes **every precinct-level JSON** file from the 2025 election dashboard and processes it into smaller datasets for use in data visualization or analysis projects.

### 🔧 Breakdown of Files

- `index.php` – Crawls and downloads JSON data of **every precinct in every barangay** in the Philippines.  
- `flatten.php` – Aggregates and compresses the data to the **city level** to make it more manageable.  
- `minify.php` – Minifies all JSON files to reduce file sizes for faster loading and storage efficiency.

---

## 📂 Output

- Complete dataset of raw precinct JSON files.
- Flattened city-level JSON data.
- Minified JSON versions for production use.

---

## 📌 Notes

- No external libraries used – pure PHP.
- Intended for educational, analytical, and civic tech use.

---

## 💖 Sponsor This Project

If you find this project useful and want to support its continued development, you can sponsor me through:

- **GitHub Sponsor**: [https://github.com/sponsors/cjdeclaro](https://github.com/sponsors/cjdeclaro)
- **Patreon**: [https://patreon.com/cjdeclaro](https://patreon.com/cjdeclaro)
- **PayPal**: [https://paypal.me/cjdeclaro](https://paypal.me/cjdeclaro)
- **Buy Me a Coffee**: [https://buymeacoffee.com/cjdeclaro](https://buymeacoffee.com/cjdeclaro)

Your support helps cover server costs, future updates, and more open-source projects like this. Thank you!

---


### 📌 About This Project

A personal project by **Christopher Jay De Claro**  
Professor, Polytechnic University of the Philippines – Sto. Tomas  
**For the people.**

**Contact Me**  
📧 cjdeclaro16@gmail.com  
📷 [Instagram](https://instagram.com/cjdeclaro)  
🔗 [LinkedIn](https://linkedin.com/in/cjdeclaro)

---

## 📝 License

This project is licensed under the [MIT License](LICENSE).  
You are free to use, modify, and distribute this code with proper attribution.
