import './bootstrap';
import Alpine from 'alpinejs';
import { Turbo } from "@hotwired/turbo"; // Import Turbo.js

window.Alpine = Alpine;
Alpine.start();

console.log("Turbo.js aktif!"); // Cek apakah Turbo berjalan
