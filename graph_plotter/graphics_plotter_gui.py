import tkinter as tk
from tkinter import ttk, messagebox
import matplotlib.pyplot as plt
import numpy as np
from decimal import Decimal, getcontext

getcontext().prec = 20


class FunctionPlotter:
    def __init__(self, root):
        self.root = root
        self.root.title("Построитель функций")

        self.function_entries = []
        self.x_value_entries = []
        self._create_widgets()

        self.root.protocol("WM_DELETE_WINDOW", self._on_closing)

    def _create_widgets(self):
        main_frame = ttk.Frame(self.root)
        main_frame.pack(padx=10, pady=10, fill=tk.BOTH, expand=True)

        for i in range(3):
            ttk.Label(main_frame, text=f"Функция {i + 1}:").grid(row=i, column=0, padx=5, pady=5, sticky="w")
            func_entry = ttk.Entry(main_frame, width=30)
            func_entry.grid(row=i, column=1, padx=5, pady=5, sticky="ew")
            self.function_entries.append(func_entry)

            ttk.Label(main_frame, text=f"Значение X {i + 1}:").grid(row=i, column=2, padx=5, pady=5, sticky="w")
            x_val_entry = ttk.Entry(main_frame, width=10)
            x_val_entry.grid(row=i, column=3, padx=5, pady=5, sticky="ew")
            self.x_value_entries.append(x_val_entry)

        input_fields = {
            "X мин": "-10",
            "X макс": "10",
            "Шаг": "0.5"
        }
        self.entry_vars = {}

        for idx, (label_text, default_value) in enumerate(input_fields.items()):
            ttk.Label(main_frame, text=label_text + ":").grid(row=3 + idx, column=0, padx=5, pady=5, sticky="w")
            entry = ttk.Entry(main_frame, width=50)
            entry.insert(0, default_value)
            entry.grid(row=3 + idx, column=1, columnspan=3, padx=5, pady=5, sticky="ew")
            if label_text == "X мин":
                self.entry_x_min = entry
            elif label_text == "X макс":
                self.entry_x_max = entry
            elif label_text == "Шаг":
                self.entry_step = entry

        plot_button = ttk.Button(main_frame, text="Построить функции", command=self._plot_functions)
        plot_button.grid(row=6, column=0, columnspan=4, pady=10)

        main_frame.grid_columnconfigure(1, weight=1)
        main_frame.grid_columnconfigure(3, weight=1)

    def _get_numeric_input(self, entry, name):
        """Вспомогательный метод для получения и валидации числового ввода."""
        value_str = entry.get().strip()
        if not value_str:
            messagebox.showerror("Ошибка ввода", f"Пожалуйста, введите значение для '{name}'.")
            return None
        try:
            return Decimal(value_str)
        except Exception:
            messagebox.showerror("Ошибка ввода", f"Неверное значение для '{name}'. Убедитесь, что это число.")
            return None

    def _plot_functions(self):
        x_min = self._get_numeric_input(self.entry_x_min, "X мин")
        x_max = self._get_numeric_input(self.entry_x_max, "X макс")
        step = self._get_numeric_input(self.entry_step, "Шаг")

        if any(v is None for v in [x_min, x_max, step]):
            return

        if x_min >= x_max or step <= 0:
            messagebox.showerror("Ошибка ввода", "Проверьте, что X мин < X макс и шаг > 0.")
            return

        x = np.arange(float(x_min), float(x_max + step / 2), float(step))

        plt.ion()
        fig, ax = plt.subplots(figsize=(10, 7))
        colors = ['red', 'blue', 'green']
        results_text_lines = []

        safe_dict_base = {
            "np": np, "Decimal": Decimal, "pi": np.pi, "e": np.e,
            **{k: getattr(np, k) for k in dir(np) if
               not k.startswith("_") and not k.startswith("array") and not k.startswith("matrix")}
        }

        for i, (func_entry, x_val_entry) in enumerate(zip(self.function_entries, self.x_value_entries)):
            function_str = func_entry.get().strip()
            x_val_for_display_str = x_val_entry.get().strip()
            current_color = colors[i % len(colors)]

            y_val_for_display = ""
            display_function_label = function_str

            try:
                if not function_str and not x_val_for_display_str:
                    continue

                if function_str.replace('.', '', 1).replace('-', '', 1).isdigit():  # Проверка на число
                    y_const = float(function_str)
                    ax.plot(x, [y_const] * len(x), label=f"y = {y_const}", linewidth=2, color=current_color)
                    display_function_label = f"y = {function_str}"

                    if x_val_for_display_str:
                        x_val_decimal = Decimal(x_val_for_display_str)
                        if x_min <= x_val_decimal <= x_max:
                            y_val_for_display = f"{float(y_const):.4f}"
                        else:
                            y_val_for_display = "X значение вне диапазона"
                    else:
                        y_val_for_display = "X значение не задано"

                elif not function_str and x_val_for_display_str:  # Вертикальная линия
                    x_val = float(x_val_for_display_str)
                    ax.axvline(x=x_val, label=f"x = {x_val}", linewidth=2, color=current_color)
                    display_function_label = f"x = {x_val_for_display_str}"
                    y_val_for_display = "Вертикальная линия (Y не применимо)"

                elif function_str:  # Математическая функция
                    safe_dict = {"x": x, **safe_dict_base}  # Добавляем 'x' для eval
                    parsed_function_str = function_str.replace("^", "**")
                    y = eval(parsed_function_str, {"__builtins__": None}, safe_dict)
                    ax.plot(x, y, label=f"y = {function_str}", linewidth=2, color=current_color)
                    display_function_label = f"y = {function_str}"

                    if x_val_for_display_str:
                        x_val_decimal = Decimal(x_val_for_display_str)
                        if x_min <= x_val_decimal <= x_max:
                            y_index = np.abs(x - float(x_val_decimal)).argmin()
                            y_val_for_display = f"{float(y[y_index]):.4f}"
                        else:
                            y_val_for_display = "X значение вне диапазона"
                    else:
                        y_val_for_display = "X значение не задано"

                # Формируем строку результата
                if x_val_for_display_str:
                    results_text_lines.append(
                        f"Ф{i + 1} ({display_function_label}) при X={float(x_val_for_display_str):.4f}: Y={y_val_for_display}")
                elif function_str or x_val_for_display_str:
                    results_text_lines.append(f"Ф{i + 1} ({display_function_label}): {y_val_for_display}")

            except Exception as e:
                msg = f"Ошибка в функции {i + 1} ('{function_str}'): {e}"
                messagebox.showerror("Ошибка функции", msg)
                results_text_lines.append(f"Ф{i + 1} ('{function_str}'): Ошибка: {e}")

        ax.set_xlabel('X')
        ax.set_ylabel('Y')
        ax.legend()
        ax.axhline(0, color='grey', linewidth=1.1, linestyle='--')
        ax.axvline(0, color='grey', linewidth=1.1, linestyle='--')
        ax.grid(True, linestyle=':', alpha=0.7)
        ax.set_xlim(float(x_min), float(x_max))
        ax.set_ylim(float(x_min), float(x_max))

        main_title = "\n".join(results_text_lines) if results_text_lines else ""
        fig.suptitle(main_title, fontsize=12, y=0.98)

        top_adjust = 0.9 - len(results_text_lines) * 0.04
        fig.subplots_adjust(top=max(top_adjust, 0.75))

        fig.canvas.draw()
        fig.canvas.flush_events()

    def _on_closing(self):
        """Метод, вызываемый при закрытии главного окна."""
        plt.close('all')
        self.root.destroy()


if __name__ == "__main__":
    root = tk.Tk()
    app = FunctionPlotter(root)
    root.mainloop()