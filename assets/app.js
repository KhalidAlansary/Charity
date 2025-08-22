import "htmx.org";
import "htmx-ext-response-targets";
import Alpine from "alpinejs";
import { createIcons, Eye, EyeOff, ListCheck, Trash2 } from "lucide";

const icons = { Eye, EyeOff, ListCheck, Trash2 };
createIcons({ icons });
window.lucide = { createIcons, icons };

Alpine.start();
