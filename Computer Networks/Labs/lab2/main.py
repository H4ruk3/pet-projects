import socket
import ssl

POP3_SERVER = 'pop.gmail.com'
POP3_PORT = 995

context = ssl.create_default_context()

try:
    with socket.create_connection((POP3_SERVER, POP3_PORT)) as server_socket:
        with context.wrap_socket(server_socket, server_hostname=POP3_SERVER) as client_socket:
            response = client_socket.recv(1024).decode()
            print(response)

            print("Введите ваше имя пользователя: ")
            user = input()
            print("Введите ваш пароль: ")
            password = input()

            client_socket.send(f'USER {user}\r\n'.encode())
            response = client_socket.recv(1024).decode()
            print(response)
            if not response.startswith('+OK'):
                raise Exception("Неверные данные для аутентификации")

            client_socket.send(f'PASS {password}\r\n'.encode())
            response = client_socket.recv(1024).decode()
            print(response)
            if not response.startswith('+OK'):
                raise Exception("Неверные данные для аутентификации")

            while True:
                print("\nВыберите команду:")
                print("1. Получить информацию о почтовом ящике (STAT)")
                print("2. Получить список сообщений (LIST)")
                print("3. Получить конкретное сообщение (RETR)")
                print("4. Удалить сообщение (DELE)")
                print("5. Проверить состояние сервера (NOOP)")
                print("6. Снять с сообщений пометки об удалении (RSET)")
                print("7. Получить заголовок и N первых строк сообщения (TOP)")
                print("8. Завершить сеанс (QUIT)")

                choice = input("Ваш выбор: ")

                if choice == "1":
                    client_socket.send('STAT\r\n'.encode())
                    response = client_socket.recv(1024).decode()
                    print(response)
                elif choice == "2":
                    print("Введите 'all' чтобы увидеть все сообщения, любую цифру для просмотра конкретного сообщения")
                    num_messages = input()
                    if num_messages == 'all':
                        client_socket.send('LIST\r\n'.encode())
                    else:
                        client_socket.send(f'LIST {num_messages}\r\n'.encode())
                    while True:
                        response = client_socket.recv(1024).decode()
                        print(response)
                        if response.endswith('\r\n'):
                            break
                elif choice == "3":
                    message_number = input("Введите номер сообщения для получения: ")
                    client_socket.send(f'RETR {message_number}\r\n'.encode())
                    while True:
                        response = client_socket.recv(1024).decode()
                        print(response)
                        if response.endswith('\r\n.\r\n'):
                            break
                elif choice == "4":
                    message_number = input("Введите номер сообщения для удаления: ")
                    client_socket.send(f'DELE {message_number}\r\n'.encode())
                    response = client_socket.recv(1024).decode()
                    print(response)
                elif choice == "5":
                    client_socket.send('NOOP\r\n'.encode())
                    response = client_socket.recv(1024).decode()
                    print(response)
                elif choice == "6":
                    client_socket.send('RSET\r\n'.encode())
                    response = client_socket.recv(1024).decode()
                    print(response)
                elif choice == "7":
                    message_number = input("Введите номер сообщения для просмотра заголовка (TOP): ")
                    lines = input("Введите количество строк для просмотра: ")
                    client_socket.send(f'TOP {message_number} {lines}\r\n'.encode())
                    while True:
                        response = client_socket.recv(1024).decode()
                        print(response)
                        if response.endswith('\r\n.\r\n'):
                            break
                elif choice == "8":
                    client_socket.send('QUIT\r\n'.encode())
                    response = client_socket.recv(1024).decode()
                    print(response)
                    break
                else:
                    print("Неверный выбор команды. Попробуйте еще раз.")

except Exception as e:
    print(f"Ошибка: {e}")
