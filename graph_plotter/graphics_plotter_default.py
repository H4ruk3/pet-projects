import re
import tkinter as tk
from tkinter import messagebox
import math


class GraphApp:
    def __init__(self, root):
        self.root = root
        self.root.title("Graph Plotter")

        self.main_frame = tk.Frame(root)
        self.main_frame.pack(fill=tk.BOTH, expand=True)

        self.left_frame = tk.Frame(self.main_frame)
        self.left_frame.pack(side=tk.LEFT, fill=tk.Y, padx=10, pady=10)

        self.equation_entries = []
        self.value_entries = []
        self.equations = []

        self.add_equation_field()

        self.add_button = tk.Button(self.left_frame, text="Добавить поле", command=self.add_equation_field)
        self.add_button.pack(pady=5)

        self.plot_button = tk.Button(self.left_frame, text="Построить график", command=self.plot_graph)
        self.plot_button.pack(pady=5)

        self.right_frame = tk.Frame(self.main_frame)
        self.right_frame.pack(side=tk.RIGHT, fill=tk.BOTH, expand=True, padx=10, pady=10)

        self.canvas = tk.Canvas(self.right_frame, bg="white")
        self.canvas.pack(fill=tk.BOTH, expand=True)

        self.function_replacements = {
            'sin': 'math.sin',
            'cos': 'math.cos',
            'tan': 'math.tan',
            'log': 'math.log',
            'sqrt': 'math.sqrt',
            'exp': 'math.exp',
            'arcsin': 'math.asin',
            'arccos': 'math.acos',
            'arctan': 'math.atan',
            'sinh': 'math.sinh',
            'cosh': 'math.cosh',
            'tanh': 'math.tanh',
            'arcsinh': 'math.asinh',
            'arccosh': 'math.acosh',
            'arctanh': 'math.atanh',
            'ctg': '1/math.tan'
        }

    def add_equation_field(self):
        if len(self.equation_entries) < 5:
            function_label = tk.Label(self.left_frame, text="Функция:")
            function_label.pack(pady=(2, 0))

            equation_var = tk.StringVar()
            equation_entry = tk.Entry(self.left_frame, textvariable=equation_var, width=30)
            equation_entry.pack(pady=2)
            self.equation_entries.append(equation_entry)
            self.equations.append(equation_var)

            value_label = tk.Label(self.left_frame, text="Значение x:")
            value_label.pack(pady=(2, 0))

            value_var = tk.StringVar()
            value_entry = tk.Entry(self.left_frame, textvariable=value_var, width=30)
            value_entry.pack(pady=2)
            self.value_entries.append(value_entry)

        else:
            messagebox.showwarning("Предупреждение", "Нельзя добавить больше 5 полей")

    def plot_graph(self):
        self.canvas.delete("all")

        width = self.canvas.winfo_width()
        height = self.canvas.winfo_height()

        self.draw_grid(width, height)

        self.canvas.create_line(width / 2, 0, width / 2, height, fill="black")
        self.canvas.create_line(0, height / 2, width, height / 2, fill="black")

        self.canvas.create_text(width - 10, height // 2 - 10, text="X", anchor=tk.SE)
        self.canvas.create_text(width // 2 - 10, 10, text="Y", anchor=tk.NW)

        self.add_axis_labels(width, height)

        for i, eq_var in enumerate(self.equations):
            equation = eq_var.get().strip()
            if equation:
                try:
                    for func in self.function_replacements:
                        equation = equation.replace(func, self.function_replacements[func])

                    if "==" in equation:
                        left_expr, right_expr = equation.split("==")
                        self.plot_contour(left_expr.strip(), right_expr.strip(), width, height)
                    elif "=" in equation:
                        var, val = equation.split("=")
                        self.plot_line(var.strip(), float(val.strip()), width, height)
                    else:
                        if "x" in equation:
                            self.plot_function(equation, width, height)
                        elif "y" in equation:
                            self.plot_function(equation, width, height, invert=True)

                    # Определяем тип функции для вычислений
                    is_inverted = False
                    if "=" not in equation:  # только для явных функций (не уравнений)
                        if "y" in equation and "x" not in equation:
                            is_inverted = True

                    value_entry = self.value_entries[i]
                    input_value = value_entry.get().strip()
                    if input_value:
                        try:
                            input_value = float(input_value)

                            if is_inverted:  # x = f(y)
                                x_value = self.evaluate_function(equation, input_value, 'y')
                                y_value = input_value
                                label = f"x={x_value:.2f}"
                            else:  # y = f(x)
                                y_value = self.evaluate_function(equation, input_value, 'x')
                                x_value = input_value
                                label = f"y={y_value:.2f}"

                            self.plot_point(x_value, y_value, width, height, label)
                        except ValueError:
                            messagebox.showerror("Ошибка", "Некорректное значение")
                        except Exception as e:
                            messagebox.showerror("Ошибка", f"Ошибка при вычислении: {e}")

                except Exception as e:
                    messagebox.showerror("Ошибка", f"Ошибка в уравнении '{equation}': {e}")
                    return

    def draw_grid(self, width, height):
        step = 20
        for x in range(0, width, step):
            self.canvas.create_line(x + 9, 0, x + 9, height, fill="lightgrey")
        for y in range(0, height, step):
            self.canvas.create_line(0, y - 1, width, y - 1, fill="lightgrey")

    def add_axis_labels(self, width, height):
        step = 20
        for x in range(width // 2 + step, width, step):
            self.canvas.create_text(x, height // 2 + 10, text=str((x - width // 2) // step), fill="black")
        for x in range(width // 2 - step, 0, -step):
            self.canvas.create_text(x, height // 2 + 10, text=str(-(width // 2 - x) // step), fill="black")
        for y in range(height // 2 + step, height, step):
            self.canvas.create_text(width // 2 + 10, y, text=str(-(y - height // 2) // step), fill="black")
        for y in range(height // 2 - step, 0, -step):
            self.canvas.create_text(width // 2 + 10, y, text=str((height // 2 - y) // step), fill="black")

    def plot_contour(self, left_expr, right_expr, width, height):
        scale = 20
        for x in range(-width // (2 * scale), width // (2 * scale)):
            for y in range(-height // (2 * scale), height // (2 * scale)):
                try:
                    X = x
                    Y = y
                    Z = eval(left_expr.replace('^', '**') + '-' + right_expr.replace('^', '**'), {"__builtins__": None},
                             {"x": X, "y": Y, "math": math})
                    if abs(Z) < 0.1:
                        screen_x = width // 2 + X * scale
                        screen_y = height // 2 - Y * scale
                        self.canvas.create_oval(screen_x - 1, screen_y - 1, screen_x + 1, screen_y + 1, fill="blue",
                                                outline="blue")
                except Exception:
                    pass

    def plot_line(self, var, val, width, height):
        if var == "x":
            screen_x = width // 2 + val * 20
            self.canvas.create_line(screen_x, 0, screen_x, height, fill="red")
        elif var == "y":
            screen_y = height // 2 - val * 20
            self.canvas.create_line(0, screen_y, width, screen_y, fill="red")

    def plot_function(self, equation, width, height, invert=False):
        scale = 20
        points = []
        for x in range(int(-width / (2 * scale) * 30), int(width / (2 * scale) * 30)):
            try:
                X = x / 30.0
                expr = equation.replace('^', '**')

                if invert:
                    Y_val = eval(equation.replace('y', 'x').replace('^', '**'), {"__builtins__": None},
                                 {"x": X, "math": math})
                    if "sin" in equation.lower() and X == 0 and Y_val == 1.0:
                        screen_x = width // 2 + Y_val * scale
                        screen_y = height // 2 - X * scale
                        points.append((screen_x, screen_y))
                        continue
                    # Проверка на комплексные числа и специальные значения
                    if isinstance(Y_val, complex) or math.isnan(Y_val) or math.isinf(Y_val):
                        continue
                    screen_x = width // 2 + Y_val * scale
                    screen_y = height // 2 - X * scale
                else:
                    Y_val = eval(equation.replace('x', 'x').replace('^', '**'), {"__builtins__": None},
                                 {"x": X, "math": math})
                    if "sin" in equation.lower() and X == 0 and Y_val == 1.0:
                        screen_x = width // 2 + X * scale
                        screen_y = height // 2 - Y_val * scale
                        points.append((screen_x, screen_y))
                        continue
                    # Проверка на комплексные числа и специальные значения
                    if isinstance(Y_val, complex) or math.isnan(Y_val) or math.isinf(Y_val):
                        continue
                    screen_x = width // 2 + X * scale
                    screen_y = height // 2 - Y_val * scale

                # Проверка координат перед добавлением
                if 0 <= screen_x <= width and 0 <= screen_y <= height:
                    points.append((screen_x, screen_y))
            except Exception:
                continue

        for i in range(len(points) - 1):
            self.canvas.create_line(points[i][0], points[i][1], points[i + 1][0], points[i + 1][1], fill="blue")

    def evaluate_function(self, equation, value, variable='x'):
        try:
            expr = equation.replace('^', '**')
            # Создаем контекст с переменными и математическими функциями
            context = {"math": math, variable: value}

            # Обработка особого случая sin(x)/x при x=0
            if variable == 'x' and value == 0 and "sin" in expr:
                # Проверяем, является ли выражение формой sin(x)/x
                test_expr = expr.replace(" ", "").lower()
                if re.match(r".*sin\(.*x.*\)/x.*", test_expr):
                    return 1.0

            return eval(expr, {"__builtins__": None}, context)
        except ZeroDivisionError:
            # Обработка деления на ноль
            return float('nan')
        except Exception:
            return float('nan')

    def plot_point(self, x_value, y_value, width, height, label):
        # Пропускаем некорректные значения
        if math.isnan(x_value) or math.isinf(x_value) or math.isnan(y_value) or math.isinf(y_value):
            return

        scale = 20
        screen_x = width // 2 + x_value * scale
        screen_y = height // 2 - y_value * scale

        # Проверка границ холста
        if not (0 <= screen_x <= width and 0 <= screen_y <= height):
            return

        self.canvas.create_oval(screen_x - 2, screen_y - 2, screen_x + 2, screen_y + 2, fill="red", outline="red")
        self.canvas.create_text(screen_x, screen_y - 10, text=label, fill="red")


if __name__ == "__main__":
    root = tk.Tk()
    app = GraphApp(root)
    root.mainloop()