import tkinter as tk
from tkinter import messagebox
import matplotlib.pyplot as plt
from matplotlib.backends.backend_tkagg import FigureCanvasTkAgg
import numpy as np
import warnings


class GraphApp:
    def __init__(self, root):
        self.root = root
        self.root.title("Graph Plotter")

        self.main_frame = tk.Frame(root)
        self.main_frame.pack(fill=tk.BOTH, expand=True)

        self.left_frame = tk.Frame(self.main_frame)
        self.left_frame.pack(side=tk.LEFT, fill=tk.Y, padx=10, pady=10)

        self.equation_entries = []
        self.equations = []

        tk.Label(self.left_frame, text="Границы X:").pack(pady=(0, 5))

        x_bounds_frame = tk.Frame(self.left_frame)
        x_bounds_frame.pack(pady=5)

        tk.Label(x_bounds_frame, text="мин:").pack(side=tk.LEFT)
        self.x_min_var = tk.StringVar(value="-10")
        self.x_min_entry = tk.Entry(x_bounds_frame, textvariable=self.x_min_var, width=8)
        self.x_min_entry.pack(side=tk.LEFT, padx=5)

        tk.Label(x_bounds_frame, text="макс:").pack(side=tk.LEFT)
        self.x_max_var = tk.StringVar(value="10")
        self.x_max_entry = tk.Entry(x_bounds_frame, textvariable=self.x_max_var, width=8)
        self.x_max_entry.pack(side=tk.LEFT, padx=5)

        tk.Label(self.left_frame, text="Границы Y:").pack(pady=(10, 5))

        y_bounds_frame = tk.Frame(self.left_frame)
        y_bounds_frame.pack(pady=5)

        tk.Label(y_bounds_frame, text="мин:").pack(side=tk.LEFT)
        self.y_min_var = tk.StringVar(value="-10")
        self.y_min_entry = tk.Entry(y_bounds_frame, textvariable=self.y_min_var, width=8)
        self.y_min_entry.pack(side=tk.LEFT, padx=5)

        tk.Label(y_bounds_frame, text="макс:").pack(side=tk.LEFT)
        self.y_max_var = tk.StringVar(value="10")
        self.y_max_entry = tk.Entry(y_bounds_frame, textvariable=self.y_max_var, width=8)
        self.y_max_entry.pack(side=tk.LEFT, padx=5)

        tk.Label(self.left_frame, text="Уравнения:").pack(pady=(10, 5))

        equation_var = tk.StringVar()
        equation_entry = tk.Entry(self.left_frame, textvariable=equation_var, width=30)
        equation_entry.pack(pady=5)
        self.equation_entries.append(equation_entry)
        self.equations.append(equation_var)

        self.plot_button = tk.Button(self.left_frame, text="Построить график", command=self.plot_graph)
        self.plot_button.pack(pady=10)

        self.x_var = tk.StringVar()
        self.y_var = tk.StringVar()
        tk.Label(self.left_frame, text="x:").pack(pady=5)
        self.x_entry = tk.Entry(self.left_frame, textvariable=self.x_var, width=10)
        self.x_entry.pack(pady=5)
        tk.Label(self.left_frame, text="y:").pack(pady=5)
        self.y_entry = tk.Entry(self.left_frame, textvariable=self.y_var, width=10, state='readonly')
        self.y_entry.pack(pady=5)

        self.calculate_button = tk.Button(self.left_frame, text="Вычислить y", command=self.calculate_y)
        self.calculate_button.pack(pady=5)

        self.right_frame = tk.Frame(self.main_frame)
        self.right_frame.pack(side=tk.RIGHT, fill=tk.BOTH, expand=True, padx=10, pady=10)

        self.figure, self.ax = plt.subplots()
        self.canvas = FigureCanvasTkAgg(self.figure, master=self.right_frame)
        self.canvas.get_tk_widget().pack(fill=tk.BOTH, expand=True)

        self.current_point = None
        self.current_annotation = None

        self.function_replacements = {
            'sin': 'np.sin',
            'cos': 'np.cos',
            'tan': 'np.tan',
            'log': 'np.log',
            'sqrt': 'np.sqrt',
            'exp': 'np.exp',
            'arcsin': 'np.arcsin',
            'arccos': 'np.arccos',
            'arctan': 'np.arctan',
            'sinh': 'np.sinh',
            'cosh': 'np.cosh',
            'tanh': 'np.tanh',
            'arcsinh': 'np.arcsinh',
            'arccosh': 'np.arccosh',
            'arctanh': 'np.arctanh',
        }

    def safe_power(self, base, exponent):
        try:
            if isinstance(exponent, (int, np.integer)):
                return base ** exponent

            if isinstance(exponent, (float, np.floating)):
                from fractions import Fraction
                frac = Fraction(exponent).limit_denominator(1000)
                numerator, denominator = frac.numerator, frac.denominator

                if denominator % 2 == 1:
                    if base < 0:
                        return -((-base) ** exponent)
                    else:
                        return base ** exponent
                else:
                    if base < 0:
                        return np.nan
                    else:
                        return base ** exponent

            return base ** exponent
        except:
            return np.nan

    def safe_eval(self, expression, x_values):
        with warnings.catch_warnings():
            warnings.simplefilter("ignore")

            normalized_expr = expression.replace(" ", "").replace("np.sin", "sin")
            if normalized_expr == "sin(x)/x" or normalized_expr == "sin(x)/x":
                result = np.ones_like(x_values)
                mask = x_values != 0
                result[mask] = np.sin(x_values[mask]) / x_values[mask]
                return result

            for func in self.function_replacements:
                expression = expression.replace(func, self.function_replacements[func])

            expression = expression.replace('^', '**')

            try:
                if '**' in expression:
                    result = np.zeros_like(x_values, dtype=np.float64)

                    for i, x in enumerate(x_values):
                        try:
                            safe_dict = {
                                'x': x,
                                'np': np,
                                'sin': np.sin,
                                'cos': np.cos,
                                'tan': np.tan,
                                'log': np.log,
                                'sqrt': np.sqrt,
                                'exp': np.exp,
                                'arcsin': np.arcsin,
                                'arccos': np.arccos,
                                'arctan': np.arctan,
                                'sinh': np.sinh,
                                'cosh': np.cosh,
                                'tanh': np.tanh,
                                'arcsinh': np.arcsinh,
                                'arccosh': np.arccosh,
                                'arctanh': np.arctanh,
                                'abs': np.abs,
                                'pi': np.pi,
                                'e': np.e
                            }

                            if '**' in expression:
                                parts = expression.split('**')
                                base_expr = parts[0]
                                exponent_expr = '**'.join(parts[1:])

                                base_val = eval(base_expr, {"__builtins__": None}, safe_dict)
                                exponent_val = eval(exponent_expr, {"__builtins__": None}, safe_dict)

                                val = self.safe_power(base_val, exponent_val)
                            else:
                                val = eval(expression, {"__builtins__": None}, safe_dict)

                            if isinstance(val, complex):
                                result[i] = np.nan
                            else:
                                result[i] = val
                        except:
                            result[i] = np.nan

                    return result
                else:
                    return eval(expression, {"__builtins__": None}, {"x": x_values, "np": np})
            except:
                return np.full_like(x_values, np.nan)

    def get_x_bounds(self):
        try:
            x_min = float(self.x_min_var.get())
            x_max = float(self.x_max_var.get())

            if x_min >= x_max:
                messagebox.showerror("Ошибка", "Минимальное значение X должно быть меньше максимального")
                return None, None

            return x_min, x_max

        except ValueError:
            messagebox.showerror("Ошибка", "Неверный формат граничных значений X")
            return None, None

    def get_y_bounds(self):
        try:
            y_min = float(self.y_min_var.get())
            y_max = float(self.y_max_var.get())

            if y_min >= y_max:
                messagebox.showerror("Ошибка", "Минимальное значение Y должно быть меньше максимального")
                return None, None

            return y_min, y_max

        except ValueError:
            messagebox.showerror("Ошибка", "Неверный формат граничных значений Y")
            return None, None

    def plot_graph(self):
        self.ax.clear()

        x_bounds = self.get_x_bounds()
        y_bounds = self.get_y_bounds()
        if x_bounds is None or y_bounds is None:
            return

        x_min, x_max = x_bounds
        y_min, y_max = y_bounds
        num_points = 400

        for eq_var in self.equations:
            equation = eq_var.get().strip()
            if equation:
                try:
                    x = np.linspace(x_min, x_max, num_points)
                    y = np.linspace(y_min, y_max, num_points)
                    X, Y = np.meshgrid(x, y)

                    if "==" in equation:
                        left_expr, right_expr = equation.split("==")
                        Z_left = self.safe_eval(left_expr, X)
                        Z_right = self.safe_eval(right_expr, Y)
                        Z = Z_left - Z_right
                        self.ax.contour(X, Y, Z, levels=[0], label=equation)
                    elif "=" in equation:
                        var, val = equation.split("=")
                        var = var.strip()
                        val = float(val.strip())
                        if var == "x":
                            self.ax.axvline(val, label=equation)
                        elif var == "y":
                            self.ax.axhline(val, label=equation)
                    elif "x" in equation:
                        y_vals = self.safe_eval(equation, x)
                        self.ax.plot(x, y_vals, label=equation)
                    elif "y" in equation:
                        x_vals = self.safe_eval(equation, y)
                        self.ax.plot(x_vals, y, label=equation)

                except Exception as e:
                    messagebox.showerror("Ошибка", f"Ошибка в уравнении '{equation}': {e}")
                    return

        self.ax.legend()
        self.ax.set_xlim(x_min, x_max)
        self.ax.set_ylim(y_min, y_max)
        self.ax.grid(True)
        self.ax.set_xlabel('x')
        self.ax.set_ylabel('y')
        self.ax.set_title('График функции')

        self.current_point = None
        self.current_annotation = None

        self.canvas.draw()

    def calculate_y(self):
        x_value = self.x_var.get()
        if not x_value:
            messagebox.showwarning("Предупреждение", "Введите значение x")
            return

        try:
            x_value = float(x_value)
        except ValueError:
            messagebox.showerror("Ошибка", "Неверный формат значения x")
            return

        for eq_var in self.equations:
            equation = eq_var.get().strip()
            if equation:
                try:
                    normalized_eq = equation.replace(" ", "").replace("np.sin", "sin")
                    if (normalized_eq == "sin(x)/x" or normalized_eq == "sin(x)/x") and abs(x_value) < 1e-10:
                        y_value = 1.0
                    else:
                        with warnings.catch_warnings():
                            warnings.simplefilter("ignore")

                            for func in self.function_replacements:
                                equation = equation.replace(func, self.function_replacements[func])

                            safe_dict = {
                                'x': x_value,
                                'np': np,
                                'sin': np.sin,
                                'cos': np.cos,
                                'tan': np.tan,
                                'log': np.log,
                                'sqrt': np.sqrt,
                                'exp': np.exp,
                                'arcsin': np.arcsin,
                                'arccos': np.arccos,
                                'arctan': np.arctan,
                                'sinh': np.sinh,
                                'cosh': np.cosh,
                                'tanh': np.tanh,
                                'arcsinh': np.arcsinh,
                                'arccosh': np.arccosh,
                                'arctanh': np.arctanh,
                                'abs': np.abs,
                                'pi': np.pi,
                                'e': np.e
                            }

                            equation = equation.replace('^', '**')

                            if '**' in equation:
                                parts = equation.split('**')
                                base_expr = parts[0]
                                exponent_expr = '**'.join(parts[1:])

                                base_val = eval(base_expr, {"__builtins__": None}, safe_dict)
                                exponent_val = eval(exponent_expr, {"__builtins__": None}, safe_dict)

                                y_value = self.safe_power(base_val, exponent_val)
                            else:
                                if 'sin(x)/x' in equation and abs(x_value) < 1e-10:
                                    y_value = 1.0
                                else:
                                    y_value = eval(equation, {"__builtins__": None}, safe_dict)

                            if isinstance(y_value, complex):
                                if abs(y_value.imag) < 1e-10:
                                    y_value = y_value.real
                                else:
                                    messagebox.showerror("Ошибка",
                                                         f"Уравнение '{equation}' дало комплексное число при x={x_value}")
                                    return

                    self.y_var.set(f"{y_value:.4f}")

                    if self.current_point:
                        self.current_point.remove()
                    if self.current_annotation:
                        self.current_annotation.remove()

                    self.current_point = self.ax.plot(x_value, y_value, 'ro', markersize=8)[0]

                    y_range = self.ax.get_ylim()[1] - self.ax.get_ylim()[0]
                    offset = 0.05 * y_range

                    self.current_annotation = self.ax.annotate(f'y = {y_value:.4f}',
                                                               xy=(x_value, y_value),
                                                               xytext=(x_value, y_value + offset),
                                                               ha='center',
                                                               color='red',
                                                               fontweight='bold',
                                                               arrowprops=dict(arrowstyle='->', color='red', lw=1))

                    self.canvas.draw()
                    return

                except Exception as e:
                    messagebox.showerror("Ошибка", f"Ошибка в уравнении '{equation}': {e}")
                    return

        messagebox.showwarning("Предупреждение", "Нет доступных уравнений для вычисления")


if __name__ == "__main__":
    root = tk.Tk()
    app = GraphApp(root)
    root.mainloop()