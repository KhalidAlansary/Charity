import Alpine from "alpinejs";
import "cally";
import "htmx.org";
import "htmx-ext-response-targets";
import {
	createIcons,
	ChevronLeft,
	ChevronRight,
	Eye,
	EyeOff,
	ListCheck,
	Trash2,
} from "lucide";

const icons = { ChevronLeft, ChevronRight, Eye, EyeOff, ListCheck, Trash2 };
createIcons({ icons });
window.lucide = { createIcons, icons };

Alpine.start();
