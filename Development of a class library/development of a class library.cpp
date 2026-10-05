#include <iostream> 
#include <vector> 
#include <string> 
#include <typeinfo> 
#include <stdexcept> 
using namespace std;
class Component {
public:
    virtual ~Component() {}
    virtual void printDetails() const = 0;
};
template <typename T>
class Item : public Component {
private:
    string name;
    T price;
public:
    Item(const string& name, const T& price)
        : name(name), price(price) {
    }
    void printDetails() const override {
        cout << "Item: " << name << ", Price: " << price << " Rub" << endl;
    }
    string getName() const {
        return name;
    }
    T getPrice() const {
        18
            return price;
    }
};
class Buildings : public Item<long double> {
public:
    using Item<long double>::Item;
    void printDetails() const override {
        cout << "Building: " << getName() << ", Price: " << getPrice() << " Rub" << endl;
    }
};
class Inventory {
private:
    vector<Component*> components;
public:
    ~Inventory() {
        for (Component* component : components) {
            delete component;
        }
    }
    void addComponent(Component* component) {
        components.push_back(component);
    }
    void removeComponent(int index) {
        if (index >= 0 && index < components.size()) {
            delete components[index];
            components.erase(components.begin() + index);
        }
        else {
            throw out_of_range("Invalid index for removing component");
        }
    }
    void printInventory() const {
        for (const Component* component : components) {
            component->printDetails();
        }
    }
    Component* getComponent(int index) const {
        if (index >= 0 && index < components.size()) {
            return components[index];
        }
        return nullptr;
    }
    bool hasComponents() const {
        return !components.empty();
    }
    void moveComponent(int index, Inventory& reserve) {
        if (index >= 0 && index < components.size()) {
            Component* componentToMove = components[index];
            components.erase(components.begin() + index);
            reserve.addComponent(componentToMove);
        }
        else {
            throw out_of_range("Invalid index for moivng component");
        }
    }
        void returnComponent(int index, Inventory & reserve) {
        if (index >= 0 && index < components.size()) {
            Component* componentToReturn = components[index];
            components.erase(components.begin() + index);
            reserve.addComponent(componentToReturn);
        }
        else {
            throw out_of_range("Invalid index for returning component");
        }
    }
    template <typename T>
    void printTotalPrice() const {
        long double totalPrice = 0;
        for (const Component* component : components) {
            const Item<T>* item = dynamic_cast<const Item<T>*>(component);
            if (item) {
                totalPrice += item->getPrice();
            }
        }
        cout << "Total Price of Components: " << totalPrice << " Rub" << endl;
    }
};
int main() {
    Inventory inventory;
    Inventory reserve;
    while (true) {
        cout << "=== MENU ===" << endl;
        cout << "1. Add Item" << endl;
        cout << "2. Add Building" << endl;
        cout << "3. Remove Component" << endl;
        cout << "4. Move/Return Component" << endl;
        cout << "5. Print Inventory" << endl;
        cout << "6. Print total price" << endl;
        cout << "7. Exit" << endl;

        int choice;
        cout << "Enter your choice: ";
        cin >> choice;

        try {
            switch (choice) {
            case 1: {
                string name;
                long double price;
                cout << "Enter item name: ";
                cin.ignore();
                getline(cin, name);
                cout << "Enter item price: ";
                if (!(cin >> price)) {
                    throw runtime_error("Invalid imput for item price");
                }
                inventory.addComponent(new Item<long double>(name, price));
                break;
            }
            case 2: {
                string name;
                long double price;
                cout << "Enter building name: ";
                cin.ignore();
                getline(cin, name);
                cout << "Enter building price: ";
                if (!(cin >> price)) {
                    throw runtime_error("Invalid imput for building price");
                }
                inventory.addComponent(new Buildings(name, price));
                break;
            }
            case 3: {
                if (!inventory.hasComponents() && !reserve.hasComponents()) {
                    cout << "No components to remove." << endl;
                    break;
                }

                int inventoryChoice;
                cout << "Select inventory to remove component from:" << endl;
                cout << "1. Inventory" << endl;
                cout << "2. Reserve Storage" << endl;
                cout << "Enter your choice: ";
                cin >> inventoryChoice;

                if (inventoryChoice == 1 && inventory.hasComponents()) {
                    int index;
                    cout << "Enter the index of the component to remove from Inventory: ";
                    if (!(cin >> index)) {
                        throw runtime_error("Invalid input for component index");
                    }
                    inventory.removeComponent(index);
                }
                else if (inventoryChoice == 2 && reserve.hasComponents()) {
                    int index;
                    cout << "Enter the index of the component to remove from Reserve Storage: ";
                    if (!(cin >> index)) {
                        throw runtime_error("Invalid input for component index");
                    }
                    reserve.removeComponent(index);
                }
                else {
                    cout << "Invalid inventory choice or no components to remove." << endl;
                }
                break;
            }
            case 4: {
                if (!inventory.hasComponents() && !reserve.hasComponents()) {
                    cout << "No components to move or return." << endl;
                    break;
                }

                int index;
                cout << "Enter the index of the component to move/return: ";
                if (!(cin >> index)) {
                    throw runtime_error("Invalid input for component index");
                }

                int inventoryChoice;
                cout << "Select inventory to move/return component from:" << endl;
                cout << "1. Inventory" << endl;
                cout << "2. Reserve Storage" << endl;
                cout << "Enter your choice: ";
                cin >> inventoryChoice;

                if (inventoryChoice == 1 && inventory.hasComponents()) {
                    if (reserve.hasComponents()) {
                        inventory.returnComponent(index, reserve);
                    }
                    else {
                        inventory.moveComponent(index, reserve);
                    }
                }
                else if (inventoryChoice == 2 && reserve.hasComponents()) {
                    if (inventory.hasComponents()) {
                        reserve.returnComponent(index, inventory);
                    }
                    else {
                        reserve.moveComponent(index, inventory);
                    }
                }
                else {
                    cout << "Invalid inventory choice or no components to move/return." << endl;
                }
                break;
            }
            case 5: {
                cout << "=== Inventory ===" << endl;
                inventory.printInventory();
                cout << "=== Reserve Storage ===" << endl;
                reserve.printInventory();
                break;
            }
            case 6: {
                cout << "=== TOTAL PRICE ===" << endl;
                cout << "1. Inventory" << endl;
                cout << "2. Reserve Storage" << endl;
                int inventoryChoice;
                cout << "Enter your choice: ";
                cin >> inventoryChoice;

                if (inventoryChoice == 1) {
                    inventory.template printTotalPrice<long double>();
                }
                else if (inventoryChoice == 2) {
                    reserve.template printTotalPrice<long double>();
                }
                else {
                    cout << "Invalid inventory choice." << endl;
                }
                break;
            }
            case 7:
                cout << "Exiting program." << endl;
                return 0;
            default:
                cout << "Invalid choice. Please try again." << endl;
            }
        }
        catch (const exception& e) {
            cout << "Error: " << e.what() << endl;
            cin.clear();
            cin.ignore(numeric_limits<streamsize>::max(), '\n');
        }
        cout << endl;
    }
}